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
		$name = $this->args->getName();
		$this->data = [
			"type" => "custom_form",
			"title" => "§e$name"."'s Profile",
			"content" => []
		];
		$this->data["content"][] = ["type" => "label", "text" => "Name: $name"];
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