<?php


namespace rain1208\guildsAPI\guilds;


use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use rain1208\guildsAPI\models\GuildLevel;

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

    }

    public function accept(string $player)
    {

    }

    public function leave(GuildPlayer $player)
    {

    }

    public function getAllGuildMember(): array
    {
        return Main::getInstance()->getDatabase()->getGuildMember($this->guildId);
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