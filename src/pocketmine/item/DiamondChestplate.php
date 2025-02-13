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

class DiamondChestplate extends Armor{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_CHESTPLATE, $meta, $count, "Diamond Chestplate");
	}

	public function getArmorTier(){
		return Armor::TIER_DIAMOND;
	}

	public function getArmorType(){
		return Armor::TYPE_CHESTPLATE;
	}

	public function getMaxDurability(){
		return 529;
	}

	public function getArmorValue(){
		return 8;
	}

	public function isChestplate(){
		return true;
	}
}
