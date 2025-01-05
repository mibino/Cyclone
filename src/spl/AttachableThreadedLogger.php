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

abstract class AttachableThreadedLogger extends ThreadedLogger{

	/** @var Volatile|\ThreadedLoggerAttachment[] */
	protected $attachments;

	public function __construct(){
		$this->attachments = new Volatile();
	}

	/**
	 * @return void
	 */
	public function addAttachment(ThreadedLoggerAttachment $attachment){
		$this->attachments[] = $attachment;
	}

	/**
	 * @return void
	 */
	public function removeAttachment(ThreadedLoggerAttachment $attachment){
		foreach($this->attachments as $i => $a){
			if($attachment === $a){
				unset($this->attachments[$i]);
			}
		}
	}

	/**
	 * @return void
	 */
	public function removeAttachments(){
		foreach($this->attachments as $i => $a){
			unset($this->attachments[$i]);
		}
	}

	/**
	 * @return \ThreadedLoggerAttachment[]
	 */
	public function getAttachments(){
		return (array) $this->attachments;
	}
}
