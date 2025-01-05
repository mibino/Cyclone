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

namespace raklib\protocol;

#include <rules/RakLibPacket.h>

use raklib\RakLib;

class UNCONNECTED_PONG extends Packet{
	public static $ID = 0x1c;

	public $pingID;
	public $serverID;
	public $serverName;

	public function encode(){
		parent::encode();
		$this->putLong($this->pingID);
		$this->putLong($this->serverID);
		$this->put(RakLib::MAGIC);
		$this->putString($this->serverName);
	}

	public function decode(){
		parent::decode();
		$this->pingID = $this->getLong();
		$this->serverID = $this->getLong();
		$this->offset += 16; //magic
		$this->serverName = $this->getString();
	}
}
