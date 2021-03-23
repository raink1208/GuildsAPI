<?php


namespace rain1208\guildsAPI;


use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use rain1208\guildsAPI\guilds\GuildManager;
use rain1208\guildsAPI\utils\ConfigManager;
use rain1208\guildsAPI\utils\SQLiteDatabase;

class Main extends PluginBase
{
    private static Main $instance;

    private SQLiteDatabase $database;

    private GuildManager $guildManager;

    private ConfigManager $configManager;

    public function onEnable()
    {
        self::$instance = $this;

        $this->database = new SQLiteDatabase($this);
        $this->guildManager = new GuildManager();
        $this->configManager = new ConfigManager();
    }

    public function onDisable()
    {

    }

    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public function getDatabase(): SQLiteDatabase
    {
        return $this->database;
    }

    public function getGuildManager(): GuildManager
    {
        return $this->guildManager;
    }

    public function getConfigManager(): ConfigManager
    {
        return $this->configManager;
    }
}