<?php


namespace rain1208\guildsAPI\guilds;


use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use rain1208\guildsAPI\utils\GuildPermission;

class GuildPlayer
{
    private string $name;

    private GuildId $guildId;
    private int $permission;

    public function __construct(string $name)
    {
        $this->name = $name;

        $database = Main::getInstance()->getDatabase();

        $data = $database->getGuildPlayerData($name);

        if ($data === null) {
            $this->guildId = new GuildId(GuildId::NO_GUILD);
            $this->permission = GuildPermission::NO_DATA;
            $database->createGuildPlayerData($this);
            return;
        }

        $this->guildId = new GuildId($data["guild_id"]);
        $this->permission = $data["permission"];

        if ($data["name"] !== null) {
            $this->setDisplayName($data["name"]);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGuildId(): GuildId
    {
        return $this->guildId;
    }

    public function getPermission(): int
    {
        return $this->permission;
    }

    public function sendMessage(string $message)
    {
        $player = Main::getInstance()->getServer()->getPlayer($this->name);

        if ($player !== null) {
            $player->sendMessage($message);
        }
    }

    public function setDisplayName(string $guildName)
    {
        $player = Main::getInstance()->getServer()->getPlayer($this->name);

        if ($player !== null) {
            $player->setDisplayName("[".$guildName."§r]" . $player->getName());
            $player->setNameTag("[".$guildName."§r]" . $player->getName());
        }
    }
}