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

interface Logger{

	/**
	 * System is unusable
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function emergency($message);

	/**
	 * Action must be taken immediately
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function alert($message);

	/**
	 * Critical conditions
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function critical($message);

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function error($message);

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function warning($message);

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function notice($message);

	/**
	 * Interesting events.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function info($message);

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function debug($message);

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed  $level
	 * @param string $message
	 *
	 * @return void
	 */
	public function log($level, $message);

	/**
	 * Logs a Throwable object
	 *
	 * @param array|null $trace
	 * @phpstan-param list<array<string, mixed>>|null $trace
	 *
	 * @return void
	 */
	public function logException(Throwable $e, $trace = null);
}
