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
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\ItemFrame as ItemFrameTile;
use pocketmine\tile\Tile;
use function mt_rand;

class ItemFrame extends Transparent{
	protected $id = self::ITEM_FRAME_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Item Frame";
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		$tile = $this->getLevel()->getTile($this);
		if(!($tile instanceof ItemFrameTile)){
			$nbt = new CompoundTag("", [
				new StringTag("id", Tile::ITEM_FRAME),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z),
				new ByteTag("ItemRotation", 0),
				new FloatTag("ItemDropChance", 1.0)
			]);
			$tile = Tile::createTile(Tile::ITEM_FRAME, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		}

		if($tile->getItem()->getId() === 0){
			$item = clone $item;
			$item->setCount(1);
			$tile->setItem($item);
			if($player instanceof Player){
				if($player->isSurvival()) {
					$count = $item->getCount();
					if(--$count <= 0){
						$player->getInventory()->setItemInHand(Item::get(Item::AIR));
						return true;
					}

					$item->setCount($count);
					$player->getInventory()->setItemInHand($item);
				}
			}
		}else{
			$itemRot = $tile->getItemRotation();
			if($itemRot === 7) $itemRot = 0;
			else $itemRot++;
			$tile->setItemRotation($itemRot);
		}

		return true;
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true, false);
	}

	public function getDrops(Item $item) : array{
		if($this->getLevel() == null){
			return [];
		}
		$tile = $this->getLevel()->getTile($this);
		if(!$tile instanceof ItemFrameTile){
			return [
				[Item::ITEM_FRAME, 0, 1]
			];
		}
		$chance = mt_rand(0, 100);
		if($chance <= ($tile->getItemDropChance() * 100)){
			return [
				[Item::ITEM_FRAME, 0 ,1],
				[$tile->getItem()->getId(), $tile->getItem()->getDamage(), 1]
			];
		}
		return [
			[Item::ITEM_FRAME, 0 ,1]
		];
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($target->isTransparent() === false and $face > 1 and $block->isSolid() === false){
			$faces = [
				2 => 3,
				3 => 2,
				4 => 1,
				5 => 0,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);
			$nbt = new CompoundTag("", [
				new StringTag("id", Tile::ITEM_FRAME),
				new IntTag("x", $block->x),
				new IntTag("y", $block->y),
				new IntTag("z", $block->z),
				new ByteTag("ItemRotation", 0),
				new FloatTag("ItemDropChance", 1.0)
			]);

			if($item->hasCustomBlockData()){
				foreach($item->getCustomBlockData() as $key => $v){
					$nbt->{$key} = $v;
				}
			}

			Tile::createTile(Tile::ITEM_FRAME, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			return true;
		}
		return false;
	}
}
