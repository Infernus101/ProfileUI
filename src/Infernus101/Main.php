<?php

namespace Infernus101;

use Infernus101\window\Handler;
use Infernus101\tasks\SaveTask;
use pocketmine\Player;
use pocketmine\OfflinePlayer;
use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
	
	public $flag = false;
	
	public function onEnable(){
		$file = "config.yml";
		if(!file_exists($this->getDataFolder() . $file)){
		@mkdir($this->getDataFolder());
		file_put_contents($this->getDataFolder() . $file, $this->getResource($file));
		}
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		if(($this->config->get("pvp-record") == 1) or ($this->config->get("kdr") == 1) or ($this->config->get("mining-record") == 1)){
		$this->flag = true;
		$this->players = [];
		$this->players = (new Config($this->getDataFolder() . "/records.json", Config::JSON))->getAll();
		$this->getServer()->getPluginManager()->registerEvents(new PlayerEvents($this), $this);
		$this->base = ["mining" => 0, "kills" => 0, "deaths" => 0];
		}
		if(is_numeric($interval = $this->config->get("auto-save-interval", 10))){ # Minutes
			if($interval > 0){
				$interval = $interval * 1200;
				$this->getScheduler()->scheduleDelayedRepeatingTask(new SaveTask($this), $interval, $interval);
			}
		}
	}
	
	public function onDisable(){
		$this->saveStat();
	}
	
	public function saveStat(){
		if($this->flag == true){
		@unlink($this->getDataFolder() . "/records.json");
		$d = new Config($this->getDataFolder() . "/records.json", Config::JSON);
			foreach($this->players as $player => $stats){
			  $d->set($player, $stats);
			}
			$d->save();
		}
	}

	public function getStat($player){
		return isset($this->players[strtolower($player->getName())]) ? $this->players[strtolower($player->getName())] : $this->base;
	}
	
	public function addStat(Player $player, String $type){
		$stat = $this->players[strtolower($player->getName())];
		$stat[$type] = $stat[$type] + 1;
		$this->players[strtolower($player->getName())] = $stat;
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool{
	  if(!$sender instanceof Player){
		  $sender->sendMessage(TextFormat::RED."> Command must be run ingame!");
		  return true;
	  }
	  if(strtolower($cmd->getName()) == 'profile'){
				if(!isset($args[0])){
				  $sender->sendMessage(TextFormat::RED."Usage: /profile <player>\n".TextFormat::GREEN."Profile UI by Infernus101! github.com/Infernus101/ProfileUI\n".TextFormat::AQUA."Server - FallenTech.tk 19132");
				  return false;
				}
				$noob = $this->getServer()->getOfflinePlayer($args[0]);
				if(!is_numeric($noob->getFirstPlayed())){
					$sender->sendMessage(TextFormat::RED."Error > Player not found");
					return false;
				}
				$handler = new Handler();
				$packet = new ModalFormRequestPacket();
				$packet->formId = $handler->getWindowIdFor(Handler::PROFILE_WINDOW);
				$packet->formData = $handler->getWindowJson(Handler::PROFILE_WINDOW, $this, $sender, $noob);
				$sender->dataPacket($packet);
		  return true;
	  }
	  return true;
	}

}
