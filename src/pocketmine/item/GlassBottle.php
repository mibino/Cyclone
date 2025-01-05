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

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\event\player\PlayerGlassBottleEvent;
use pocketmine\level\Level;
use pocketmine\Player;

class GlassBottle extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GLASS_BOTTLE, $meta, $count, "Glass Bottle");
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		if($player === null or $player->isSurvival() !== true){
			return false;
		}
		if($target->getId() === Block::STILL_WATER or $target->getId() === Block::WATER){
			$player->getServer()->getPluginManager()->callEvent($ev = new PlayerGlassBottleEvent($player, $target, $this));
			if($ev->isCancelled()){
				return false;
			}else{
				if($this->count <= 1){
					$player->getInventory()->setItemInHand(Item::get(Item::POTION, 0, 1));
					return true;
				}else{
					$this->count--;
					$player->getInventory()->setItemInHand($this);
				}
				if($player->getInventory()->canAddItem(Item::get(Item::POTION, 0, 1)) === true){
					$player->getInventory()->AddItem(Item::get(Item::POTION, 0, 1));
				}else{
					$motion = $player->getDirectionVector()->multiply(0.4);
					$position = clone $player->getPosition();
					$player->getLevel()->dropItem($position->add(0 , 0.5, 0), Item::get(Item::POTION, 0, 1) , $motion, 40);
				}
				return true;
			}
		}
		return false;
	}
}
