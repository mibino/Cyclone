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

namespace pocketmine\utils;

#include <rules/DataPacket.h>
#ifndef COMPILE
#endif
use pocketmine\item\Item;
use function chr;
use function ord;
use function strlen;
use function substr;

class BinaryStream extends \stdClass{
	public $offset;
	public $buffer;

	public function __construct($buffer = "", $offset = 0){
		$this->buffer = $buffer;
		$this->offset = $offset;
	}

	public function reset(){
		$this->buffer = "";
		$this->offset = 0;
	}

	public function setBuffer($buffer = null, $offset = 0){
		$this->buffer = $buffer;
		$this->offset = (int) $offset;
	}

	public function getOffset(){
		return $this->offset;
	}

	public function getBuffer(){
		return $this->buffer;
	}

	public function get($len){
		if($len < 0){
			$this->offset = strlen($this->buffer) - 1;
			return "";
		}elseif($len === true){
			$str = substr($this->buffer, $this->offset);
			$this->offset = strlen($this->buffer);
			return $str;
		}
		return $len === 1 ? $this->buffer[$this->offset++] : substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	public function put($str){
		$this->buffer .= $str;
	}

	public function getBool() : bool{
		return (bool) $this->getByte();
	}

	public function putBool($v){
		$this->putByte((bool) $v);
	}

	public function getLong(){
		return Binary::readLong($this->get(8));
	}

	public function putLong($v){
		$this->buffer .= Binary::writeLong($v);
	}

	public function getInt(){
		return Binary::readInt($this->get(4));
	}

	public function putInt($v){
		$this->buffer .= Binary::writeInt($v);
	}

	public function getLLong(){
		return Binary::readLLong($this->get(8));
	}

	public function putLLong($v){
		$this->buffer .= Binary::writeLLong($v);
	}

	public function getLInt(){
		return Binary::readLInt($this->get(4));
	}

	public function putLInt($v){
		$this->buffer .= Binary::writeLInt($v);
	}

	public function getSignedShort(){
		return Binary::readSignedShort($this->get(2));
	}

	public function putShort($v){
		$this->buffer .= Binary::writeShort($v);
	}

	public function getShort(){
		return Binary::readShort($this->get(2));
	}

	public function putSignedShort($v){
		$this->buffer .= Binary::writeShort($v);
	}

	public function getFloat(){
		return Binary::readFloat($this->get(4));
	}

	public function putFloat($v){
		$this->buffer .= Binary::writeFloat($v);
	}

	public function getLShort($signed = true){
		return $signed ? Binary::readSignedLShort($this->get(2)) : Binary::readLShort($this->get(2));
	}

	public function putLShort($v){
		$this->buffer .= Binary::writeLShort($v);
	}

	public function getLFloat(){
		return Binary::readLFloat($this->get(4));
	}

	public function putLFloat($v){
		$this->buffer .= Binary::writeLFloat($v);
	}

	public function getTriad(){
		return Binary::readTriad($this->get(3));
	}

	public function putTriad($v){
		$this->buffer .= Binary::writeTriad($v);
	}

	public function getLTriad(){
		return Binary::readLTriad($this->get(3));
	}

	public function putLTriad($v){
		$this->buffer .= Binary::writeLTriad($v);
	}

	public function getByte(){
		return ord($this->buffer[$this->offset++]);
	}

	public function putByte($v){
		$this->buffer .= chr($v);
	}

	public function getDataArray($len = 10){
		$data = [];
		for($i = 1; $i <= $len and !$this->feof(); ++$i){
			$data[] = $this->get($this->getTriad());
		}
		return $data;
	}

	public function putDataArray(array $data = []){
		foreach($data as $v){
			$this->putTriad(strlen($v));
			$this->put($v);
		}
	}

	public function getUUID(){
		//This is actually two little-endian longs: UUID Most followed by UUID Least
		$part1 = $this->getLInt();
		$part0 = $this->getLInt();
		$part3 = $this->getLInt();
		$part2 = $this->getLInt();
		return new UUID($part0, $part1, $part2, $part3);
	}

	public function putUUID(UUID $uuid){
		$this->putLInt($uuid->getPart(1));
		$this->putLInt($uuid->getPart(0));
		$this->putLInt($uuid->getPart(3));
		$this->putLInt($uuid->getPart(2));
	}

	public function getSlot(){
		$id = $this->getVarInt();

		if($id <= 0){
			return Item::get(0, 0, 0);
		}
		$auxValue = $this->getVarInt();
		$data = $auxValue >> 8;
		$cnt = $auxValue & 0xff;

		$nbtLen = $this->getLShort();
		$nbt = "";

		if($nbtLen > 0){
			$nbt = $this->get($nbtLen);
		}
		return Item::get(
			$id,
			$data,
			$cnt,
			$nbt
		);
	}

	public function putSlot(Item $item){
		if($item->getId() === 0){
			$this->putVarInt(0);
			return;
		}

		$this->putVarInt($item->getId());
		$auxValue = (($item->getDamage() ?? -1) << 8) | $item->getCount();
		$this->putVarInt($auxValue);
		$nbt = $item->getCompoundTag();
		$this->putLShort(strlen($nbt));
		$this->put($nbt);
	}

	public function getString(){
		return $this->get($this->getUnsignedVarInt());
	}

	public function putString($v){
		$this->putUnsignedVarInt(strlen($v));
		$this->put($v);
	}

	//TODO: varint64

	/**
	 * Reads an unsigned varint32 from the stream.
	 */
	public function getUnsignedVarInt(){
		return Binary::readUnsignedVarInt($this);
	}

	/**
	 * Writes an unsigned varint32 to the stream.
	 */
	public function putUnsignedVarInt($v){
		$this->put(Binary::writeUnsignedVarInt($v));
	}

	/**
	 * Reads a signed varint32 from the stream.
	 */
	public function getVarInt(){
		return Binary::readVarInt($this);
	}

	/**
	 * Writes a signed varint32 to the stream.
	 */
	public function putVarInt($v){
		$this->put(Binary::writeVarInt($v));
	}

	public function getEntityId(){
		return $this->getVarInt();
	}

	public function putEntityId($v){
		$this->putVarInt($v);
	}

	public function getBlockCoords(&$x, &$y, &$z){
		$x = $this->getVarInt();
		$y = $this->getByte();
		$z = $this->getVarInt();
	}

	public function putBlockCoords($x, $y, $z){
		$this->putVarInt($x);
		$this->putByte($y);
		$this->putVarInt($z);
	}

	public function getVector3f(&$x, &$y, &$z){
		$x = $this->getLFloat();
		$y = $this->getLFloat();
		$z = $this->getLFloat();
	}

	public function putVector3f($x, $y, $z){
		$this->putLFloat($x);
		$this->putLFloat($y);
		$this->putLFloat($z);
	}

	public function feof(){
		return !isset($this->buffer[$this->offset]);
	}
}
