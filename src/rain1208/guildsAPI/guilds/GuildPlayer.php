<?php


namespace rain1208\guildsAPI\guilds;


use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use rain1208\guildsAPI\utils\GuildPermission;
use rain1208\guildsAPI\wrapper\EconomyPlugin;

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

    public function setGuildId(int $id)
    {
        $this->guildId = new GuildId($id);
    }

    public function getPermission(): int
    {
        return $this->permission;
    }

    public function setPermission(int $id)
    {
        $this->permission = $id;
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

    public function getMoney(): int
    {
        return EconomyPlugin::myMoney($this->name);
    }

    public function getInfoString(): string
    {
        $data = $this->getInfo();

        $msg  = "プレイヤーのID: " . $data["name"] . "\n";
        $msg .= "所持金: " . $data["money"] . "\n";
        $msg .= "参加中のギルド: " . $data["guild"] . " ギルドID: " . $data["guildId"] . "\n";
        $msg .= "ギルドの権限: " . $data["permission"];

        return $msg;
    }

    public function getInfo(): array
    {
        $data = [
            "name" => $this->getName(),
            "money" => $this->getMoney(),
            "guild" => "未参加",
            "guildId" => "未参加",
            "permission" => "未参加"
        ];

        $guild = Main::getInstance()->getGuildManager()->getGuild($this->getGuildId());

        if ($guild !== null) {
            $permissions = ["オーナー", "管理者", "メンバー", "認証待ち"];
            $data["guild"] = $guild->getName();
            $data["guildId"] = $guild->getGuildId()->getValue();
            $data["permission"] = $permissions[$this->getPermission()];
        }

        return $data;
    }

    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "guild_id" => $this->guildId,
            "permission" => $this->permission
        ];
    }
}