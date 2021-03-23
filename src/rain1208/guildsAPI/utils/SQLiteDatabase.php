<?php


namespace rain1208\guildsAPI\utils;


use pocketmine\plugin\PluginLogger;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\guilds\GuildPlayer;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use SQLite3;

class SQLiteDatabase
{
    private SQlite3 $db;
    private PluginLogger $logger;

    public function __construct(Main $plugin)
    {
        $this->logger = $plugin->getLogger();

        $f = $plugin->getDataFolder()."guilds.db";
        if (file_exists($f)) {
            $this->db = new SQLite3($f, SQLITE3_OPEN_READWRITE);
        } else {
            $this->db = new SQLite3($f, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        }

        $this->db->exec("CREATE TABLE IF NOT EXISTS guilds(id INTEGER NOT NULL PRIMARY KEY, name TEXT NOT NULL UNIQUE, level INTEGER NOT NULL, exp INTEGER NOT NULL)");
        $this->db->exec("CREATE TABLE IF NOT EXISTS players(id TEXT PRIMARY KEY, guild_id INTEGER NOT NULL, permission INTEGER NOT NULL)");
    }

    public function createGuildData(Guild $guild)
    {
        $stmt = $this->db->prepare("INSERT INTO guilds (id, name, level, exp) VALUES (:id, :name, :level, :exp)");

        $stmt->bindValue(":id", $guild->getGuildId()->getValue());
        $stmt->bindValue(":name", $guild->getName());
        $stmt->bindValue(":level", $guild->getGuildLevel()->getLevel());
        $stmt->bindValue(":exp", $guild->getGuildLevel()->getExp());

        $stmt->execute();
    }

    public function saveGuildData(Guild $guild)
    {
        $stmt = $this->db->prepare("UPDATE guilds SET level=:level, exp=:exp WHERE id=:guild_id");

        $stmt->bindValue(":guild_id", $guild->getGuildId()->getValue());
        $stmt->bindValue(":level", $guild->getGuildLevel()->getLevel());
        $stmt->bindValue(":exp", $guild->getGuildLevel()->getExp());

        $stmt->execute();
    }

    public function getAllGuildData(): array
    {
        $result = [];

        $stmt = $this->db->query("SELECT guild_id, name, level, exp, p.id FROM guilds inner join players p on guilds.id = p.guild_id WHERE permission=0");

        while ($res = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $res;
        }

        return $result;
    }

    /**
     * @param int|GuildId $id
     * @return string[]
     */
    public function getGuildMember($id): array
    {
        if ($id instanceof GuildId) {
            $id = $id->getValue();
        }

        $stmt = $this->db->prepare("SELECT id FROM players WHERE guild_id=:guild_id");

        $stmt->bindValue(":guild_id", $id);

        $stmt = $stmt->execute();

        $result = [];

        while ($res = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $res["id"];
        }

        return $result;
    }

    public function createGuildPlayerData(GuildPlayer $player)
    {
        $stmt = $this->db->prepare("INSERT INTO players (id, guild_id, permission) VALUES (:id, :guild_id, :permission)");

        $stmt->bindValue(":id", $player->getName());
        $stmt->bindValue(":guild_id", $player->getGuildId()->getValue());
        $stmt->bindValue(":permission", $player->getPermission());

        $stmt->execute();
    }

    public function getGuildPlayerData(string $name): ?array
    {
        $stmt = $this->db->prepare("SELECT players.id, guild_id, permission, g.name FROM players left outer join guilds g on players.guild_id = g.id WHERE players.id=:name");

        $stmt->bindValue(":name", $name);

        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        return $result !== false ? $result : null;
    }
}