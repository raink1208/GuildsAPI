<?php


namespace rain1208\guildsAPI;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        Main::getInstance()->getGuildPlayerManager()->loadPlayer($event->getPlayer()->getName());
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($event->getPlayer()->getName());

        Main::getInstance()->getGuildPlayerManager()->savePlayer($player);
    }
}