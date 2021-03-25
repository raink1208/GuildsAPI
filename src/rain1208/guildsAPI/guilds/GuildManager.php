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
    }

    public function getGuilds(): array
    {
        return array_values($this->guilds);
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

        return $this->guilds[$id] ?: null;
    }

    public function createGuild(string $name, string $owner)
    {
        $config = Main::getInstance()->getConfigManager()->get(ConfigManager::NO_EDIT);
        $id = $config->get("GuildUsedIDLast") + 1;
        $config->set("GuildUsedIDLast", $id);
        $config->save();

        $guild = new Guild(new GuildId($id), $name, new GuildLevel(0, 0), $owner);

        $this->nameList[$name] = $id;
        $this->guilds[$id] = $guild;

        Main::getInstance()->getDatabase()->createGuildData($guild);
    }

    public function saveGuild(Guild $guild)
    {
        Main::getInstance()->getDatabase()->saveGuildData($guild);
    }
}