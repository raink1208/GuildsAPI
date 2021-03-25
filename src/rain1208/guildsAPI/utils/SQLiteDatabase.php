<?php


namespace rain1208\guildsAPI\utils;


use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
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

        $this->db->executeGeneric("guildsql.init.guilds");
        $this->db->executeGeneric("guildsql.init.players");

        $file = $plugin->getDataFolder().$plugin->getConfig()->get("database")["sqlite"]["file"];
        $this->syncDB = new SQLite3($file, SQLITE3_OPEN_READONLY);
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

    public function close()
    {
        $this->db->waitAll();

        if (isset($this->db)) {
            $this->db->close();
        }

        $this->syncDB->close();
    }
}