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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class NetherBrickFence extends Transparent{

	protected $id = self::NETHER_BRICK_FENCE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getBreakTime(Item $item){
		if($item instanceof Air){
			//Breaking by hand
			return 10;
		}else{
			// Other breaktimes are equal to woodfences.
			return parent::getBreakTime($item);
		}
	}

	public function getHardness(){
		return 2;
	}

	public function getToolType(){
		//Different then the woodfences
		return Tool::TYPE_PICKAXE;
	}

	public function getName() : string{
		return "Nether Brick Fence";
	}

	public function canConnect(Block $block){
		return ($block instanceof NetherBrickFence) or ($block->isSolid() and !$block->isTransparent());
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_WOODEN){
			return [
				[Item::NETHER_BRICK_FENCE, $this->meta, 1],
			];
		}else{
			return [];
		}
	}
}
