<?php


namespace rain1208\guildsAPI\wrapper;


use pocketmine\event\Listener;
use rain1208\guildsAPI\Main;
use rain1208\killLevel\LevelUPEvent;

class KillLevelEventListener implements Listener
{
    public function onLevelUp(LevelUPEvent $event)
    {
        Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($event->getPlayer()->getName())->loadDisplayName();
    }
}