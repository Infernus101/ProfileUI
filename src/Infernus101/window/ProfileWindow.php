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

		$name2 = ucfirst($name);
		$this->data = [
			"type" => "custom_form",
			"title" => TextFormat::AQUA.TextFormat::BOLD."$name2"."'s Profile",
			"content" => []
		];

		$this->data["content"][] = ["type" => "label", "text" => "Name: $name2"];

		if($this->pl->config->get("rank") == 1){
		$this->data["content"][] = ["type" => "label", "text" => "Rank: $rank"];
		}

		if($this->pl->config->get("money") == 1){
		$this->data["content"][] = ["type" => "label", "text" => "Money: $money"];
		}

		if($this->pl->config->get("faction") == 1){
		$this->data["content"][] = ["type" => "label", "text" => "Faction: $fac"];
		}

		if($this->pl->config->get("first-played") == 1){
		$this->data["content"][] = ["type" => "label", "text" => "First Played: $date2 at $time2"];
		}

		if($this->pl->config->get("last-seen") == 1){
			if($flag == true){
			$this->data["content"][] = ["type" => "label", "text" => "Status: $status"];
			}
			if($flag == false){
			$this->data["content"][] = ["type" => "label", "text" => "Status: $status"];
			$this->data["content"][] = ["type" => "label", "text" => "Last seen: $date at $time"];	
			}
		}

	}
	private function select($index){
		$handler = new Handler();
	}

	public function handle(ModalFormResponsePacket $packet): bool {
		$index = (int) $packet->formData + 1;
		$this->select($index);
		return true;
	}
}
