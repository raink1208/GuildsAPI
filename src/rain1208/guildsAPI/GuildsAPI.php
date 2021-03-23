<?php


namespace rain1208\guildsAPI;


class GuildsAPI
{
    private static GuildsAPI $instance;

    public static function getInstance(): GuildsAPI
    {
        if (!isset(self::$instance)) {
            self::$instance = new GuildsAPI();
        }

        return self::$instance;
    }

    public function getGuilds(): array
    {
        return Main::getInstance()->getGuildManager()->getGuilds();
    }

}