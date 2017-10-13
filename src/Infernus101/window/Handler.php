<?php

namespace Infernus101\window;

use Infernus101\Main;
use Infernus101\window\ProfileWindow;
use Infernus101\window\Window;
use pocketmine\Player;

class Handler {

	const PROFILE_WINDOW = 0;

	private $types = [
		ProfileWindow::class,
	];

	public function getWindowJson(int $windowId, Main $loader, Player $player, $args): string {
		return $this->getWindow($windowId, $loader, $player, $args)->getJson();
	}

	public function getWindow(int $windowId, Main $loader, Player $player, $args): Window {
		if(!isset($this->types[$windowId])) {
			throw new \OutOfBoundsException("Tried to get window of non-existing window ID.");
		}
		return new $this->types[$windowId]($loader, $player, $args);
	}

	public function isInRange(int $windowId): bool {
		if(isset($this->types[$windowId]) || isset($this->types[$windowId + 4000])) {
			return true;
		}
		return false;
	}

	public function getWindowIdFor(int $windowId): int {
		if($windowId >= 4000) {
			return $windowId - 4000;
		}
		return 4000 + $windowId;
	}
}
