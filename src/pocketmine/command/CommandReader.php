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

namespace pocketmine\command;

use pocketmine\Thread;
use pocketmine\utils\MainLogger;
use pocketmine\utils\Utils;
use function extension_loaded;
use function feof;
use function fgets;
use function fopen;
use function getopt;
use function is_resource;
use function readline_add_history;
use function readline_callback_handler_install;
use function readline_callback_handler_remove;
use function readline_callback_read_char;
use function stream_select;
use function trim;

class CommandReader extends Thread{
	private $readline;
	/** @var \Threaded */
	protected $buffer;
	private $shutdown = false;
	/** @var MainLogger */
	private $logger;

	public function __construct($logger){
		$opts = getopt("", ["disable-readline"]);
		$this->readline = (extension_loaded("readline") and !isset($opts["disable-readline"]));
		$this->logger = $logger;
		$this->buffer = new \Threaded();
		$this->start();
	}

	public function shutdown(){
		$this->shutdown = true;
	}

	private function readline_callback($line){
		if($line !== ""){
			$this->buffer[] = $line;
			readline_add_history($line);
		}
	}

	private function readLine(){
		if(!$this->readline){
			global $stdin;
			$line = trim(fgets($stdin));
			if($line !== ""){
				$this->buffer[] = $line;
			}
		}else{
			readline_callback_read_char();
		}
	}

	/**
	 * Reads a line from console, if available. Returns null if not available
	 *
	 * @return string|null
	 */
	public function getLine(){
		if($this->buffer->count() !== 0){
			return $this->buffer->shift();
		}

		return null;
	}

	public function quit(){
		// Windows sucks
		if(Utils::getOS() !== "win"){
			parent::quit();
		}
	}

	public function run(){
		global $stdin;
		$stdin = fopen("php://stdin", "r");
		if($this->readline){
			readline_callback_handler_install("> ", [$this, "readline_callback"]);
			$this->logger->setConsoleCallback("readline_redisplay");
		}

		while(!$this->shutdown){
			$r = [$stdin];
			$w = null;
			$e = null;
			if(stream_select($r, $w, $e, 0, 200000) > 0){
				// PHP on Windows sucks
				if(feof($stdin)){
					if(Utils::getOS() == "win"){
						$stdin = fopen("php://stdin", "r");
						if(!is_resource($stdin)){
							break;
						}
					}else{
						break;
					}
				}
				$this->readLine();
			}
		}

		if($this->readline){
			$this->logger->setConsoleCallback(null);
			readline_callback_handler_remove();
		}
	}

	public function getThreadName(){
		return "Console";
	}
}
