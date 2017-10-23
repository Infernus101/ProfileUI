<?php

namespace Infernus101\tasks;

use pocketmine\scheduler\PluginTask;
use Infernus101\Main;

class SaveTask extends PluginTask{

	public $pl;
	
	public function __construct(Main $pl){
		parent::__construct($pl);
		$this->pl = $pl;
	}

	public function onRun($currentTick){
		$this->pl->saveStat();
	}
}
