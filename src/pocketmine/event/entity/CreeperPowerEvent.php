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

namespace pocketmine\event\entity;

use pocketmine\entity\Creeper;
use pocketmine\entity\Lightning;
use pocketmine\event\Cancellable;

class CreeperPowerEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	const CAUSE_SET_ON = 0;
	const CAUSE_SET_OFF = 1;
	const CAUSE_LIGHTNING = 2;

	/** @var  Lightning */
	private $lightning;

	private $cause;

	public function __construct(Creeper $creeper, Lightning $lightning = null, int $cause = self::CAUSE_LIGHTNING){
		$this->entity = $creeper;
		$this->lightning = $lightning;
		$this->cause = $cause;
	}

	public function getLightning(){
		return $this->lightning;
	}

	public function getCause(){
		return $this->cause;
	}
}
