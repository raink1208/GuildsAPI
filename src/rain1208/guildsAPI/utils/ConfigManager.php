<?php


namespace rain1208\guildsAPI\utils;


use pocketmine\utils\Config;
use rain1208\guildsAPI\Main;

class ConfigManager
{
    const NO_EDIT = 0;
    const SETTING = 1;

    private array $configs = [];

    public function __construct()
    {
        $f = Main::getInstance()->getDataFolder();

        $this->configs[self::NO_EDIT] = new Config($f."NO_EDIT.yml", Config::YAML, array("GuildUsedIDLast" => 0));
        $this->configs[self::SETTING] = new Config($f."Setting.yml", Config::YAML, array("GuildCreateNeedMoney" => 100000));
    }

    public function get(int $id): Config
    {
        if (!isset($this->configs[$id])) {
            throw new \OutOfBoundsException("ConfigのIDの値が範囲外です");
        }

        return $this->configs[$id];
    }
}