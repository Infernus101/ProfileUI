<?php

namespace Infernus101;

use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;

class PlayerEvents implements Listener {
	
	public $pl;
	
	public function __construct(Main $pg) {
		$this->pl = $pg;
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		if($this->pl->flag == true){
			if(!isset($this->pl->players[strtolower($ev->getPlayer()->getName())])){
			  $this->pl->players[strtolower($ev->getPlayer()->getName())] = $this->pl->base;
			}
		}
	}
	
	public function onBreak(BlockBreakEvent $ev){
		if($ev->isCancelled()){
		  return;
		}
		if($this->pl->config->get("mining-record") == 1){
		$p = $ev->getPlayer();
		$this->pl->addStat($p, 'mining');
		}
	}
	
	public function onDeath(PlayerDeathEvent $event){
		if(($this->pl->config->get("pvp-record") == 1) or ($this->pl->config->get("kdr") == 1)){
			$p = $event->getEntity();
			if($p->getLastDamageCause() instanceof EntityDamageByEntityEvent){
				$killer = $p->getLastDamageCause()->getDamager();
				if($killer instanceof Player){
					if(strtolower($killer->getName()) != strtolower($p->getName())){
					$this->pl->addStat($p, 'deaths');
					$this->pl->addStat($killer, 'kills');
					}
				}
			}
		}
	}
}
