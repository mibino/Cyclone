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

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerGlassBottleEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Block */
	private $target;
	/** @var Item */
	private $item;

	public function __construct(Player $Player, Block $target, Item $itemInHand){
		$this->player = $Player;
		$this->target = $target;
		$this->item = $itemInHand;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

	public function setItem(Item $item){
		$this->item = $item;
	}

	/**
	 * @return Block
	 */
	public function getBlock(){
		return $this->target;
	}
}
