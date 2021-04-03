<?php


namespace rain1208\guildsAPI;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use rain1208\guildsAPI\utils\ConfigManager;

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

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $config = Main::getInstance()->getConfigManager()->get(ConfigManager::SETTING);
        $bool = $config->get("IntraGuildPVP") ?: false;
        if ($bool) {
            return;
        }
        $p1 = $event->getEntity();
        $p2 = $event->getDamager();

        if ($p1 instanceof Player && $p2 instanceof Player) {
            $gp1 = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($p1->getName());
            $gp2 = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($p2->getName());

            if ($gp1->getGuildId()->equals($gp2->getGuildId())) {
                $event->setCancelled();
            }
        }
    }
}