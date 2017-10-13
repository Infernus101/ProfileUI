<?php

namespace Infernus101\window;

use Infernus101\Main;
use Infernus101\window\Handler;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;

abstract class Window {

	protected $pl = null;
	protected $player = null;
	protected $args = null;
	protected $data = [];

	public function __construct(Main $pl, Player $player, $args) {
		$this->pl = $pl;
		$this->player = $player;
		$this->args = $args;
		$this->process();
	}

	public function getJson(): string {
		return json_encode($this->data);
	}

	public function getLoader(): Loader {
		return $this->pl;
	}

	public function getPlayer(): Player {
		return $this->player;
	}
	
	public function getProfilePlayer(): Player {
		return $this->args;
	}

	public function navigate(int $menu, Player $player, Handler $handler, $args): void {
		$packet = new ModalFormRequestPacket();
		$packet->formId = $handler->getWindowIdFor($menu);
		$packet->formData = $handler->getWindowJson($menu, $this->pl, $player, $args);
		$player->dataPacket($packet);
	}

	protected abstract function process(): void;

	public abstract function handle(ModalFormResponsePacket $packet): bool;
}
