<?php


namespace rain1208\guildsAPI\guilds;


use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use rain1208\guildsAPI\models\GuildLevel;
use rain1208\guildsAPI\utils\ConfigManager;
use rain1208\guildsAPI\utils\GuildPermission;

class GuildManager
{
    private array $guilds;
    private array $nameList;

    public function __construct()
    {
        $this->guilds = [];
        $this->nameList = [];

        $this->loadGuilds();
    }

    private function loadGuilds()
    {
        $guilds = Main::getInstance()->getDatabase()->getAllGuildData();

        foreach ($guilds as $guild) {
            $this->loadGuild($guild["guild_id"], $guild["name"], $guild["level"], $guild["exp"], $guild["id"]);
        }
    }

    public function loadGuild(int $guildID, string $name, int $level, int $exp, string $owner)
    {
        $data = Main::getInstance()->getDatabase()->getGuildMember($guildID);

        $admin = $data[GuildPermission::admin];
        $member = $data[GuildPermission::member];

        $members = array_merge($admin, $member);

        $wait = $data[GuildPermission::wait];

        $this->guilds[$guildID] = new Guild(new GuildId($guildID), $name, new GuildLevel($level, $exp), $owner, $members, $wait);
        $this->nameList[$name] = $guildID;
    }

    /** @return Guild[] */
    public function getGuilds(): array
    {
        return array_values($this->guilds);
    }

    public function getNameList(): array
    {
        return $this->nameList;
    }

    public function getMoneySortGuilds(): array
    {
        $data = [];

        foreach ($this->getGuilds() as $guild) {
            $data[$guild->getGuildId()->getValue()] = $guild->totalMemberMoney();
        }

        natsort($data);

        $guilds = [];

        foreach (array_keys($data) as $guildId) {
            $guilds[] = $this->getGuild($guildId);
        }

        return array_reverse($guilds);
    }

    /**
     * @param int|GuildId
     * @return Guild|null
     */
    public function getGuild($id): ?Guild
    {
        if ($id instanceof GuildId) {
            $id = $id->getValue();
        }

        if ($id === -1) return null;

        return $this->guilds[$id] ?: null;
    }

    public function createGuild(string $name, string $owner): Guild
    {
        $config = Main::getInstance()->getConfigManager()->get(ConfigManager::NO_EDIT);
        $id = $config->get("GuildUsedIDLast") + 1;
        $config->set("GuildUsedIDLast", $id);
        $config->save();

        $guild = new Guild(new GuildId($id), $name, new GuildLevel(0, 0), $owner);

        $this->nameList[$name] = $id;
        $this->guilds[$id] = $guild;

        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($owner);

        if ($guildPlayer === null) return $guild;

        $guildPlayer->setPermission(GuildPermission::OWNER);
        $guildPlayer->setGuildId($guild->getGuildId()->getValue());
        Main::getInstance()->getGuildPlayerManager()->savePlayer($guildPlayer);

        Main::getInstance()->getDatabase()->createGuildData($guild);

        return $guild;
    }

    public function deleteGuild(Guild $guild)
    {
        $data = Main::getInstance()->getDatabase()->getGuildMember($guild->getGuildId());
        $members = array_merge($data[0], $data[1], $data[2], $data[3]);

        Main::getInstance()->getDatabase()->deleteGuildData($guild->getGuildId());

        foreach ($members as $member) {
            $player = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($member);
            $player->setGuildId(GuildId::NO_GUILD);
            $player->setPermission(GuildPermission::NO_DATA);
            Main::getInstance()->getGuildPlayerManager()->savePlayer($player);
            $player->sendMessage("ギルドが削除されました");
        }

        unset($this->guilds[$guild->getGuildId()->getValue()]);
    }

    public function getGuildCreateNeedMoney(): int
    {
        $needMoney = Main::getInstance()->getConfigManager()->get(ConfigManager::SETTING)->get("GuildCreateNeedMoney");

        if (!is_int($needMoney)) $needMoney = 100000;
        return $needMoney;
    }

    public function nameExists(string $name): bool
    {
        return in_array($name, array_keys($this->nameList));
    }

    public function saveGuild(Guild $guild)
    {
        Main::getInstance()->getDatabase()->saveGuildData($guild);
    }

    public function saveAll()
    {
        foreach ($this->guilds as $guildId => $guild) {
            $this->saveGuild($guild);
        }
    }
}