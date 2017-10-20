<?php

namespace Infernus101\window;

use Infernus101\Main;
use Infernus101\window\Window;
use Infernus101\window\Handler;
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;

class ProfileWindow extends Window {
	public function process(): void {

		$flag = true;
		$name = $this->args->getName();
		$manager = $this->pl->getServer()->getPluginManager();

		if($this->pl->config->get("rank") == 1){
			$pp = $manager->getPlugin("PurePerms");
			if(!is_null($func = $pp->getUserDataMgr()->getGroup($this->args))){
				$rank = $func->getName();
			}
		else{
			$rank = '-';
		}
		}

		if($this->pl->config->get("money") == 1){
			$eco = $manager->getPlugin("EconomyAPI");
			$money = $eco->myMoney($name);
			if($money == false){
				$money = '-';
			}
		}

		if($this->pl->config->get("faction") == 1){
			$f = $manager->getPlugin("FactionsPro");
			if($f->isInFaction($name)){
			$fac = $f->getPlayerFaction($name);
			}
		else{
			$fac = '-';	
		}
		}

		if($this->pl->config->get("last-seen") == 1){
			if($this->args instanceof Player){
				$status = 'Online';
				$flag = true;
			}
		else{
			$status = 'Offline';
			$date = date("l, F j, Y", ($last = $this->args->getLastPlayed() / 1000));
			$time = date("h:ia", $last);
			$flag = false;
		}
		}

		if($this->pl->config->get("first-played") == 1){
			$date2 = date("l, F j, Y", ($first = $this->args->getFirstPlayed() / 1000));
			$time2 = date("h:ia", $first);
		}
		
		if($this->pl->config->get("mining-record") == 1){
			$stat = $this->pl->getStat($this->args);
			$mined = $stat["mining"];
		}
		
		if($this->pl->config->get("pvp-record") == 1){
			$stat = $this->pl->getStat($this->args);
			$kills = $stat["kills"];
			$deaths = $stat["deaths"];
		}
		
		if($this->pl->config->get("kdr") == 1){
			if($kills > 0 and $deaths > 0){
			$kdr = round($kills/$deaths);
			}
			else{
			$kdr = 'N/A';
			}
		}

		$name2 = ucfirst($name);
		$this->data = [
			"type" => "custom_form",
			"title" => TextFormat::AQUA.TextFormat::BOLD."$name2"."'s Profile",
			"content" => []
		];

		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Name: ".TextFormat::WHITE."$name2"];

		if($this->pl->config->get("rank") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Rank: ".TextFormat::WHITE."$rank"];
		}

		if($this->pl->config->get("money") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Money: ".TextFormat::WHITE."$money"];
		}

		if($this->pl->config->get("faction") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Faction: ".TextFormat::WHITE."$fac"];
		}
		
		if($this->pl->config->get("mining-record") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Blocks broken: ".TextFormat::WHITE."$mined"];
		}
		
		if($this->pl->config->get("pvp-record") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Kills: ".TextFormat::WHITE."$kills"];
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Deaths: ".TextFormat::WHITE."$deaths"];
		}
		
		if($this->pl->config->get("kdr") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Kills/Deaths: ".TextFormat::WHITE."$kdr"];
		}

		if($this->pl->config->get("first-played") == 1){
		$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."First Played: ".TextFormat::WHITE."$date2 at $time2"];
		}

		if($this->pl->config->get("last-seen") == 1){
			if($flag == true){
			$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Status: ".TextFormat::WHITE."$status"];
			}
			if($flag == false){
			$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Status: ".TextFormat::WHITE."$status"];
			$this->data["content"][] = ["type" => "label", "text" => TextFormat::GOLD."Last seen: ".TextFormat::WHITE."$date at $time"];	
			}
		}

	}

	public function handle(ModalFormResponsePacket $packet): bool {
		return true;
	}
}
