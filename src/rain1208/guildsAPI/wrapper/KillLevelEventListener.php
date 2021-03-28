<?php


namespace rain1208\guildsAPI\wrapper;


use rain1208\guildsAPI\EventListener;
use rain1208\guildsAPI\Main;
use rain1208\killLevel\LevelUPEvent;

class KillLevelEventListener extends EventListener
{
    public function onLevelUp(LevelUPEvent $event)
    {
        Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($event->getPlayer()->getName())->loadDisplayName();
    }
}