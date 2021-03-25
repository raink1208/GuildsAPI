<?php


namespace rain1208\guildsAPI\guilds;


class GuildPlayerManager
{
    private array $players;

    public function __construct()
    {
        $this->players = [];
    }

    public function loadPlayer(string $player)
    {
        $this->players[$player] = new GuildPlayer($player);
    }

    public function getGuildPlayer(string $player): GuildPlayer
    {
        if (!in_array($player, $this->players)) {
            $this->loadPlayer($player);
        }

        return $this->players[$player];
    }
}