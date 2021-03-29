<?php


namespace rain1208\guildsAPI\utils;


use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\guilds\GuildPlayer;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;
use SQLite3;

class SQLiteDatabase
{
    private SQLite3 $syncDB;
    private DataConnector $db;

    public function __construct(Main $plugin)
    {
        $this->db = libasynql::create(
            $plugin,
            $plugin->getConfig()->get("database"),
            [
                "sqlite" => "sqls/sqlite.sql"
            ]
        );

        $file = $plugin->getDataFolder().$plugin->getConfig()->get("database")["sqlite"]["file"];

        $db = new SQLite3($file,SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        $db->exec("CREATE TABLE IF NOT EXISTS guilds(id INTEGER NOT NULL PRIMARY KEY, name TEXT NOT NULL UNIQUE, level INTEGER NOT NULL, exp INTEGER NOT NULL)");
        $db->exec("CREATE TABLE IF NOT EXISTS players(id TEXT PRIMARY KEY, guild_id INTEGER NOT NULL, permission INTEGER NOT NULL)");

        $this->syncDB = new SQLite3($file, SQLITE3_OPEN_READONLY);
    }

    public function createGuildData(Guild $guild)
    {
        $this->db->executeInsert(
            "guildsql.guild.create",
            [
                "id" => $guild->getGuildId()->getValue(),
                "name" => $guild->getName(),
                "level" => $guild->getGuildLevel()->getLevel(),
                "exp" => $guild->getGuildLevel()->getExp()
            ],
            function () use ($guild) {
                Main::getInstance()->getLogger()->info($guild->getName()."を作成しました");
            }
        );
    }

    public function deleteGuildData(GuildId $guildId)
    {
        $this->db->executeGeneric(
            "guildsql.guild.delete",
            [
                "guild_id" => $guildId->getValue()
            ]
        );
    }

    public function saveGuildData(Guild $guild)
    {
        $this->db->executeChange(
            "guildsql.guild.save",
            [
                "guild_id" => $guild->getGuildId()->getValue(),
                "level" => $guild->getGuildLevel()->getLevel(),
                "exp" => $guild->getGuildLevel()->getExp()
            ],
            function () use($guild) {
                Main::getInstance()->getLogger()->info($guild->getName()."を保存しました");
            }
        );
    }

    public function getAllGuildData(): array
    {
        $result = [];

        $stmt = $this->syncDB->query("SELECT guild_id, name, level, exp, p.id FROM guilds inner join players p on guilds.id = p.guild_id WHERE permission=0");

        while ($res = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $res;
        }

        return $result;
    }


    /**
     * @param int|GuildId $id
     * @return array
     */
    public function getGuildMember($id): array
    {
        if ($id instanceof GuildId) {
            $id = $id->getValue();
        }

        $stmt = $this->syncDB->prepare("SELECT id, permission FROM players WHERE guild_id=:guild_id");

        $stmt->bindValue(":guild_id", $id);

        $stmt = $stmt->execute();

        $result = [[],[],[],[]];

        while ($res = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $result[$res["permission"]][] = $res["id"];
        }

        return $result;
    }

    public function createGuildPlayerData(GuildPlayer $player)
    {
        $this->db->executeInsert(
            "guildsql.player.create",
            [
                "id" => $player->getName(),
                "guild_id" => $player->getGuildId()->getValue(),
                "permission" => $player->getPermission()
            ],
            function () use ($player) {

            }
        );
    }

    public function savePlayerData(GuildPlayer $player)
    {
        $this->db->executeChange(
            "guildsql.player.save",
            [
                "name" => $player->getName(),
                "guild_id" => $player->getGuildId()->getValue(),
                "permission" => $player->getPermission()
            ],
            function () use ($player) {

            }
        );
    }

    public function getGuildPlayerData(string $name): ?array
    {
        $stmt = $this->syncDB->prepare("SELECT id, guild_id, permission FROM players WHERE id=:name");

        $stmt->bindValue(":name", $name);

        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        return $result !== false ? $result : null;
    }

    public function getGuildPlayerDataNameList(): array
    {
        $stmt = $this->syncDB->query("SELECT id FROM players");

        $result = [];

        while ($res = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $res["id"];
        }

        return $result;
    }

    public function close()
    {
        $this->db->waitAll();

        if (isset($this->db)) {
            $this->db->close();
        }

        $this->syncDB->close();
    }
}