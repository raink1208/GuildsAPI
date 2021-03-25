<?php


namespace rain1208\guildsAPI;


use pocketmine\plugin\PluginBase;
use rain1208\guildsAPI\guilds\GuildManager;
use rain1208\guildsAPI\guilds\GuildPlayerManager;
use rain1208\guildsAPI\utils\ConfigManager;
use rain1208\guildsAPI\utils\SQLiteDatabase;

class Main extends PluginBase
{
    private static Main $instance;

    private SQLiteDatabase $database;

    private GuildManager $guildManager;
    private GuildPlayerManager $guildPlayerManager;
    private ConfigManager $configManager;

    public function onEnable()
    {
        self::$instance = $this;

        $this->saveDefaultConfig();
        $this->reloadConfig();

        $this->database = new SQLiteDatabase($this);
        $this->guildManager = new GuildManager();
        $this->guildPlayerManager = new GuildPlayerManager();
        $this->configManager = new ConfigManager();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->registerCommand();
    }

    public function onDisable()
    {
        $this->database->close();
    }

    public function registerCommand()
    {
        $map = $this->getServer()->getCommandMap();
        $commands = [
            "guild" => "rain1208\guildsAPI\commands\GuildCommand"
        ];

        foreach ($commands as $command => $class) {
            $map->register("guildsAPI", new $class($command, $this));
        }
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

    public function getGuildPlayerManager(): GuildPlayerManager
    {
        return $this->guildPlayerManager;
    }

    public function getConfigManager(): ConfigManager
    {
        return $this->configManager;
    }
}