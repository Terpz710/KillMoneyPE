<?php

namespace Terpz710\KillMoneyPE;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;

class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        $eco = $this->getConfig()->get("eco");
        if (Server::getInstance()->getPluginManager()->getPlugin($eco) === null) {
            $this->getLogger()->info("KILLMONEY DISABLE, NO PLUGIN $eco");
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getEntity();
        $cause = $player->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $sender = $cause->getDamager();
            if ($sender instanceof Player) {
                $pl = Server::getInstance()->getPluginManager()->getPlugin($this->getConfig()->get("eco"));
                if ($this->getConfig()->get("chance") == true) {
                    if (mt_rand(1, $this->getConfig()->get("chance.money")) === 1) {
                        $moneyAmount = $this->getConfig()->get("money");
                        $pl->addMoney($sender, $moneyAmount);
                        $sender->sendMessage("§l§f(§a!§f)§r§f You received§a $$moneyAmount §ffor the kill!");
                    }
                } else {
                    $moneyAmount = $this->getConfig()->get("money");
                    $pl->addMoney($sender, $moneyAmount);
                    $sender->sendMessage("§l§f(§a!§f)§r§f You received§a $$moneyAmount §ffor the kill!");
                }
            }
        }
    }
}