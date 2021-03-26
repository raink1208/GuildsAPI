<?php


namespace rain1208\guildsAPI\guilds;


use onebone\economyapi\EconomyAPI;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use rain1208\guildsAPI\models\GuildLevel;
use rain1208\guildsAPI\utils\GuildPermission;

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

        Main::getInstance()->getDatabase()->savePlayerData($player);
    }

    public function accept(string $player)
    {
        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player);

        $guildPlayer->setPermission(GuildPermission::member);
        Main::getInstance()->getDatabase()->savePlayerData($guildPlayer);

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

        Main::getInstance()->getDatabase()->savePlayerData($player);
    }

    public function getAllGuildMember(): array
    {
        $result[] = $this->owner;

        $result += $this->members;

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
            $amount += EconomyAPI::getInstance()->myMoney($member);
        }

        return $amount;
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