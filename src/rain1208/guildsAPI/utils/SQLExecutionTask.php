<?php


namespace rain1208\guildsAPI\utils;


use pocketmine\scheduler\AsyncTask;
use SQLite3Stmt;

class SQLExecutionTask extends AsyncTask
{
    private SQLite3Stmt $stmt;

    public function __construct(SQLite3Stmt $stmt)
    {
        $this->stmt = $stmt;
    }

    public function onRun()
    {
        $this->stmt->execute();
    }
}