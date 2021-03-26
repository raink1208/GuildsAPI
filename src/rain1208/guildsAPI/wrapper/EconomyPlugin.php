<?php


namespace rain1208\guildsAPI\wrapper;


use onebone\economyapi\EconomyAPI;
use pocketmine\Player;
use pocketmine\utils\UUID;
use rain1208\guildsAPI\guilds\GuildPlayer;
use rain1208\guildsAPI\Main;
use TypeError;

class EconomyPlugin
{

    public static function myMoney($player): float
    {
        $player = self::castPlayer($player);

        $money = EconomyAPI::getInstance()->myMoney($player);

        return $money ? $money : 0.0;
    }

    public static function reduceMoney($player ,float $amount)
    {
        $player = self::castPlayer($player);

        EconomyAPI::getInstance()->reduceMoney($player, $amount);
    }


    public static function hasEnoughMoney($player, float $amount): bool
    {
        $money = self::myMoney($player);

        return $money >= $amount;
    }

    private static function castPlayer($player): string
    {
        if ($player instanceof GuildPlayer) return $player->getName();
        if ($player instanceof Player) return $player->getName();
        if (is_string($player)) return $player;

        throw new TypeError();
    }
}