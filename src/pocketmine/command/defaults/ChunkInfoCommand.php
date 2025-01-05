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

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\level\format\mcregion\McRegion;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use function count;

class ChunkInfoCommand extends VanillaCommand{
	public function __construct($name){
		parent::__construct(
			$name,
			"Gets the information of a chunk or regenerate a chunk",
			"/chunkinfo (x) (y) (z) (levelName) (regenerate)"
		);
		$this->setPermission("pocketmine.command.chunkinfo");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(!$sender instanceof Player and count($args) < 4){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($sender instanceof Player and count($args) < 4){
			$pos = $sender->getPosition();
		}else{
			$level = $sender->getServer()->getLevelByName($args[3]);
			if(!$level instanceof Level){
				$sender->sendMessage(TextFormat::RED . "Invalid level name");

				return false;
			}
			$pos = new Position((int) $args[0], (int) $args[1], (int) $args[2], $level);
		}

		if(!isset($args[4]) or $args[0] != "regenerate"){
			$chunk = $pos->getLevel()->getChunk($pos->x >> 4, $pos->z >> 4);
			McRegion::getRegionIndex($chunk->getX(), $chunk->getZ(), $x, $z);

			$sender->sendMessage("Region X: $x Region Z: $z");
		}elseif($args[4] == "regenerate"){
			foreach($sender->getServer()->getOnlinePlayers() as $p){
				if($p->getLevel() == $pos->getLevel()){
					$p->kick(TextFormat::AQUA . "A chunk of this chunk is regenerating, please re-login.", false);
				}
			}
			$pos->getLevel()->regenerateChunk($pos->x >> 4, $pos->z >> 4);
		}

		return true;
	}
}
