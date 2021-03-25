<?php


namespace rain1208\guildsAPI;


use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\guilds\GuildPlayer;

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

    public function getGuild($id): ?Guild
    {
        return Main::getInstance()->getGuildManager()->getGuild($id);
    }

    public function getGuildPlayer(string $player): ?GuildPlayer
    {
        return Main::getInstance()->getGuildPlayerManager()->getAPIGuildPlayer($player);
    }

    public function matchGuild(string $player1, string $player2): bool
    {
        $p1 = $this->getGuildPlayer($player1);
        $p2 = $this->getGuildPlayer($player2);

        if ($p1 === null || $p2 === null) return false;

        return $p1->getGuildId()->equals($p2->getGuildId());
    }
}