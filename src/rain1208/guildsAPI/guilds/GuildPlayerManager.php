<?php


namespace rain1208\guildsAPI\guilds;


use pocketmine\Player;

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

    public function getGuildPlayer(Player $player)
    {
        if (!in_array($player->getName(), $this->players)) {
            $this->loadPlayer($player->getName());
        }

        return $this->players[$player->getName()];
    }
}