<?php


namespace rain1208\guildsAPI\guilds;


use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use rain1208\guildsAPI\models\GuildLevel;
use rain1208\guildsAPI\utils\GuildPermission;
use rain1208\guildsAPI\wrapper\EconomyPlugin;

class Guild
{
    private string $name;

    private GuildId $guildId;
    private GuildLevel $guildLevel;

    private string $owner;
    private array $members;

    private array $wait;

    /**
     * @param GuildId $guildId
     * @param string $name
     * @param GuildLevel $guildLevel
     * @param string $owner
     * @param string[] $member
     * @param array $wait
     */
    public function __construct(GuildId $guildId, string $name, GuildLevel $guildLevel, string $owner, array $member = [], array $wait = [])
    {
        $this->name = $name;

        $this->guildId = $guildId;
        $this->guildLevel = $guildLevel;

        $this->owner = $owner;
        $this->members = $member;
        $this->wait = $wait;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwnerName(): string
    {
        return $this->owner;
    }

    public function getGuildId(): GuildId
    {
        return $this->guildId;
    }

    public function getGuildLevel(): GuildLevel
    {
        return $this->guildLevel;
    }

    public function join(GuildPlayer $player)
    {
        $this->wait[] = $player->getName();

        $player->setGuildId($this->guildId->getValue());
        $player->setPermission(GuildPermission::wait);

        Main::getInstance()->getGuildPlayerManager()->savePlayer($player);
        Main::getInstance()->getGuildManager()->saveGuild($this);

        $player->sendMessage($this->getName()."への参加申請を受け取りました");

        $owner = $this->getMemberHasPermission(GuildPermission::OWNER);
        $admin = $this->getMemberHasPermission(GuildPermission::admin);

        /** @var GuildPlayer $player */
        foreach (array_merge($owner, $admin) as $player) {
            $player->sendMessage($player->getName()."がギルドに参加申請をしています");
        }
    }

    public function accept(string $player)
    {
        if (in_array($player, $this->wait)) {
            $index = array_search($player, $this->wait);
            array_splice($this->wait ,$index);
        }

        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player);

        $guildPlayer->setPermission(GuildPermission::member);

        $this->members[] = $player;

        Main::getInstance()->getGuildPlayerManager()->savePlayer($guildPlayer);
        Main::getInstance()->getGuildManager()->saveGuild($this);

        $this->broadcastMessage($player."がギルドに参加しました");
    }

    public function leave(GuildPlayer $player)
    {
        if (in_array($player->getName(), $this->wait)) {
            $index = array_search($player->getName(), $this->wait);
            array_splice($this->wait ,$index);
        }

        if (in_array($player->getName(), $this->members)) {
            $index = array_search($player->getName(), $this->members);
            array_splice($this->members ,$index);
        }

        $player->setGuildId(GuildId::NO_GUILD);
        $player->setPermission(GuildPermission::NO_DATA);

        Main::getInstance()->getGuildPlayerManager()->savePlayer($player);
        Main::getInstance()->getGuildManager()->saveGuild($this);
    }

    public function getAllGuildMember(): array
    {
        $result[] = array_merge($this->members,[$this->owner]);

        return $result;
    }

    /**
     * @param int $permission
     * @return GuildPlayer[]
     */
    public function getMemberHasPermission(int $permission): array
    {
        $result = [];
        if ($permission === GuildPermission::OWNER) {
            $result[] = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($this->owner);
            return $result;
        }

        $data = Main::getInstance()->getDatabase()->getGuildMember($this->guildId)[$permission];
        foreach ($data as $player) {
            $result[] = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player);
        }

        return $result;
    }

    public function addExp(int $exp)
    {
        $hasExp = $this->guildLevel->getExp();
        $level = $this->guildLevel->getLevel();

        if ($hasExp + $exp >= $need = $this->getNeedExp($level)) {
            $this->guildLevel->setExp($hasExp+$exp-$need);
            $this->guildLevel->levelUp();
        }
    }

    public function getNeedExp(int $level): int
    {
        return 50000*sqrt($level);
    }

    public function broadcastMessage(string $message)
    {
        $manager = Main::getInstance()->getGuildPlayerManager();

        foreach ($this->members as $member) {
            $manager->getGuildPlayer($member)->sendMessage($message);
        }
    }

    public function totalMemberMoney(): int
    {
        $amount = 0;

        foreach ($this->getAllGuildMember() as $member) {
            $amount += EconomyPlugin::myMoney($member);
        }

        return $amount;
    }

    public function getGuildInfoString(): string
    {
        $info = $this->getGuildInfo();

        $msg = "ギルド名: " . $info["name"] . "\n";
        $msg .= "ギルドのオーナー: " . $info["owner"] . "\n";
        $msg .= "所持金合計: " . $info["totalMoney"] . "\n";
        $msg .= "ギルドのレベル(exp): " . $info["level"] . "(" . $info["exp"] . ")\n\n";
        $msg .= "ギルドメンバー情報" . "\n";
        $msg .= "管理者数: " . $info["memberCount"][GuildPermission::admin] . "\n";
        $msg .= "メンバー数: " . $info["memberCount"][GuildPermission::member] . "\n";
        $msg .= "認証待ち数: " . $info["memberCount"][GuildPermission::wait];

        return $msg;
    }

    public function getGuildInfo(): array
    {
        $owner = $this->owner;
        $money = $this->totalMemberMoney();
        $memberCount = [];

        $data = Main::getInstance()->getDatabase()->getGuildMember($this->guildId);
        for ($i = 0; $i <= 3; $i++) {
            $memberCount[$i] = count($data[$i]);
        }

        return [
            "name" => $this->getName(),
            "level" => $this->guildLevel->getLevel(),
            "exp" => $this->guildLevel->getExp(),
            "owner" => $owner,
            "totalMoney" => $money,
            "memberCount" => $memberCount
        ];
    }

    public function toArray(): array
    {
        return [
            "guildId" => $this->getGuildId()->getValue(),
            "name" => $this->getName(),
            "level" => $this->guildLevel->getLevel(),
            "exp" => $this->guildLevel->getExp()
        ];
    }
}