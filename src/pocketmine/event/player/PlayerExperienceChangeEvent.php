<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

namespace pocketmine\event\player;

use pocketmine\entity\Human;
use pocketmine\event\Cancellable;

class PlayerExperienceChangeEvent extends PlayerEvent implements Cancellable{

	/** @deprecated */
	const ADD_EXPERIENCE = 0;
	const SET_EXPERIENCE = 1;

	public static $handlerList = null;

	public $progress;
	public $expLevel;

	public function __construct(Human $player, int $expLevel, float $progress){
		$this->progress = $progress;
		$this->expLevel = $expLevel;
		$this->player = $player;
	}

	/**
	 * @deprecated This is redundant, and will be removed in the future.
	 */
	public function getAction(){
		return self::SET_EXPERIENCE;
	}

	public function getExpLevel(){
		return $this->expLevel;
	}

	public function setExpLevel($level){
		$this->expLevel = $level;
	}

	public function getProgress() : float{
		return $this->progress;
	}

	public function setProgress(float $progress){
		$this->progress = $progress; //errors will be handled internally anyway
	}

	public function getExp(){
		return Human::getLevelXpRequirement($this->expLevel) * $this->progress;
	}

	public function setExp($exp){
		$this->progress = $exp / Human::getLevelXpRequirement($this->expLevel);
	}
}
