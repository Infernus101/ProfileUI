<?php

namespace Infernus101\tasks;

use pocketmine\scheduler\Task;
use Infernus101\Main;

class SaveTask extends Task{

	public $pl;
	
	public function __construct(Main $pl){
		$this->pl = $pl;
	}

	public function onRun($currentTick){
		$this->pl->saveStat();
	}
}
