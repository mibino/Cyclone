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

namespace pocketmine\network\protocol;

use function explode;

class StrangePacket extends DataPacket{
	const NETWORK_ID = 0x1b;

	public $address;
	public $port = 19132;

	public function pid(){
		return 0x1b;
	}

	protected function putAddress($addr, $port, $version = 4){
		$this->putByte($version);
		if($version === 4){
			foreach(explode(".", $addr) as $b){
				$this->putByte((~((int) $b)) & 0xff);
			}
			$this->putShort($port);
		}else{
			//IPv6
		}
	}

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putAddress($this->address, $this->port);
	}

}
