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

/**
 * @phpstan-extends \SplFixedArray<int|string>
 */
class SplFixedByteArray extends SplFixedArray{

	/** @var bool */
	private $convert;

	/**
	 * @param int  $size
	 * @param bool $convert
	 */
	public function __construct($size, $convert = false){
		parent::__construct($size);
		$this->convert = (bool) $convert;
	}

	/**
	 * @param int  $start
	 * @param int  $size
	 * @param bool $normalize
	 *
	 * @return string|string[]
	 */
	public function chunk($start, $size, $normalize = true){
		$end = $start + $size;
		if($normalize and $this->convert){
			$d = "";
			for($i = $start; $i < $end; ++$i){
				/** @var int $v */
				$v = $this[$i];

				$d .= chr($v);
			}
		}else{
			$d = [];
			for($i = $start; $i < $end; ++$i){
				/** @var string $v */
				$v = $this[$i];
				$d[] = $v;
			}
		}
		return $d;
	}

	/**
	 * @param string $str
	 * @param bool   $convert
	 *
	 * @return SplFixedByteArray
	 */
	public static function fromString($str, $convert = false){
		$len = strlen($str);
		$ob = new SplFixedByteArray($len, $convert);

		if($convert){
			for($i = 0; $i < $len; ++$i){
				$ob[$i] = ord($str[$i]);
			}
		}else{
			for($i = 0; $i < $len; ++$i){
				$ob[$i] = $str[$i];
			}
		}

		return $ob;
	}

	/**
	 * @param string $str
	 * @param int    $size
	 * @param int    $start
	 * @param bool   $convert
	 *
	 * @return SplFixedByteArray
	 */
	public static function fromStringChunk($str, $size, $start = 0, $convert = false){
		$ob = new SplFixedByteArray($size, $convert);

		if($convert){
			for($i = 0; $i < $size; ++$i){
				$ob[$i] = ord($str[$i + $start]);
			}
		}else{
			for($i = 0; $i < $size; ++$i){
				$ob[$i] = $str[$i + $start];
			}
		}

		return $ob;
	}

	/**
	 * @return string
	 */
	public function toString(){
		$result = "";
		if($this->convert){
			for($i = 0; $i < $this->getSize(); ++$i){
				/** @var int $v */
				$v = $this[$i];
				$result .= chr($v);
			}
		}else{
			for($i = 0; $i < $this->getSize(); ++$i){
				/** @var string $v */
				$v = $this[$i];
				$result .= $v;
			}
		}
		return $result;
	}

	public function __toString(){
		return $this->toString();
	}
}
