<?php


namespace rain1208\guildsAPI\utils;


use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use rain1208\guildsAPI\Main;

class SQLiteDatabase
{
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
    }

    public function close()
    {
        $this->db->waitAll();

        if (isset($this->db)) {
            $this->db->close();
        }
    }
}