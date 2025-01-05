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

class OPEN_CONNECTION_REQUEST_2 extends Packet{
	public static $ID = 0x07;

	public $clientID;
	public $serverAddress;
	public $serverPort;
	public $mtuSize;

	public function encode(){
		parent::encode();
		$this->put(RakLib::MAGIC);
		$this->putAddress($this->serverAddress, $this->serverPort, 4);
		$this->putShort($this->mtuSize);
		$this->putLong($this->clientID);
	}

	public function decode(){
		parent::decode();
		$this->offset += 16; //Magic
		$this->getAddress($this->serverAddress, $this->serverPort);
		$this->mtuSize = $this->getShort();
		$this->clientID = $this->getLong();
	}
}
