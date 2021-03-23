<?php


namespace rain1208\guildsAPI;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        Main::getInstance()->getGuildPlayerManager()->loadPlayer($event->getPlayer()->getName());
    }
}