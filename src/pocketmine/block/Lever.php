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
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Lever extends RedstoneSource{
	protected $id = self::LEVER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getName() : string{
		return "Lever";
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$side = $this->getDamage();
			if($this->isActivated()) $side ^= 0x08;
			$faces = [
				5 => 0,
				6 => 0,
				3 => 2,
				1 => 4,
				4 => 3,
				2 => 5,
				0 => 1,
				7 => 1,
			];

			$block = $this->getSide($faces[$side]);
			if($block->isTransparent()){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($target->isTransparent() === false){
			$faces = [
				3 => 3,
				2 => 4,
				4 => 2,
				5 => 1,
			];
			if($face === 0){
				$to = $player instanceof Player ? $player->getDirection() : 0;
				$this->meta = ($to % 2 != 1 ? 0 : 7);
			}elseif($face === 1){
				$to = $player instanceof Player ? $player->getDirection() : 0;
				$this->meta = ($to % 2 != 1 ? 6 : 5);
			}else{
				$this->meta = $faces[$face];
			}
			$this->getLevel()->setBlock($block, $this, true, false);
			return true;
		}
		return false;
	}

	public function activate(array $ignore = []){
		parent::activate($ignore);
		$side = $this->meta;
		if($this->isActivated()) $side ^= 0x08;
		$faces = [
			5 => 0,
			6 => 0,
			3 => 2,
			1 => 4,
			4 => 3,
			2 => 5,
			0 => 1,
			7 => 1,
		];

		$block = $this->getSide($faces[$side])->getSide(Vector3::SIDE_UP);
		if(!$this->equals($block)){
			$this->activateBlock($block);
		}

		$this->checkTorchOn($this->getSide($faces[$side]),[$this->getOppositeSide($faces[$side])]);
	}

	public function deactivate(array $ignore = []){
		parent::deactivate($ignore);
		$side = $this->meta;
		if($this->isActivated()) $side ^= 0x08;
		$faces = [
			5 => 0,
			6 => 0,
			3 => 2,
			1 => 4,
			4 => 3,
			2 => 5,
			0 => 1,
			7 => 1,
		];

		$block = $this->getSide($faces[$side])->getSide(Vector3::SIDE_UP);
		if(!$this->equals($block)){
			$this->deactivateBlock($block);
		}

		$this->checkTorchOff($this->getSide($faces[$side]),[$this->getOppositeSide($faces[$side])]);
	}

	public function onActivate(Item $item, Player $player = null){
		$this->meta ^= 0x08;
		$this->getLevel()->setBlock($this, $this, true, false);
		if($this->isActivated()) $this->activate();
		else $this->deactivate();
		return true;
	}

	public function onBreak(Item $item){
		if($this->isActivated()){
			$this->meta ^= 0x08;
			$this->getLevel()->setBlock($this, $this, true, false);
			$this->deactivate();
		}
		$this->getLevel()->setBlock($this, new Air(), true, false);
	}

	public function isActivated(Block $from = null){
		return (($this->meta & 0x08) === 0x08);
	}

	public function getHardness() {
		return 0.5;
	}

	public function getResistance(){
		return 2.5;
	}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, 0 ,1],
		];
	}
}
