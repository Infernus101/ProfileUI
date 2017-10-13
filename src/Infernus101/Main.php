<?php

namespace Infernus101;

use Infernus101\window\Handler;
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

	public function onEnable(){
		$this->getServer()->getLogger()->notice("[ProfielUI] Enabled! - By Infernus101");
		$file = "config.yml";
		if(!file_exists($this->getDataFolder() . $file)){
		@mkdir($this->getDataFolder());
		file_put_contents($this->getDataFolder() . $file, $this->getResource($file));
		}
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	}
	
	public function onDisable(){
		$this->getServer()->getLogger()->notice("[ProfielUI] Disabled! - By Infernus101");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool{
	  if(!$sender instanceof Player){
		  $sender->sendMessage(TextFormat::RED."> Command must be run ingame!");
		  return true;
	  }
	  if(strtolower($cmd->getName()) == 'profile'){
			  if(!isset($args[0])){
				  $sender->sendMessage(TextFormat::GOLD."Usage: /profile <player>");
				  return false;
			  }
				$noob = $this->getServer()->getOfflinePlayer($args[0]);
				if(!$noob instanceof OfflinePlayer and !$noob instanceof Player){
				$sender->sendMessage(TextFormat::RED."> Player not found!");
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
