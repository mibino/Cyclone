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

interface ClassLoader{

	public function __construct(ClassLoader $parent = null);

	/**
	 * Adds a path to the lookup list
	 *
	 * @param string $path
	 * @param bool   $prepend
	 *
	 * @return void
	 */
	public function addPath($path, $prepend = false);

	/**
	 * Removes a path from the lookup list
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	public function removePath($path);

	/**
	 * Returns an array of the classes loaded
	 *
	 * @return string[]
	 */
	public function getClasses();

	/**
	 * Returns the parent ClassLoader, if any
	 *
	 * @return ClassLoader|null
	 */
	public function getParent();

	/**
	 * Attaches the ClassLoader to the PHP runtime
	 *
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function register($prepend = false);

	/**
	 * Called when there is a class to load
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function loadClass($name);

	/**
	 * Returns the path for the class, if any
	 *
	 * @param string $name
	 *
	 * @return string|null
	 */
	public function findClass($name);
}
