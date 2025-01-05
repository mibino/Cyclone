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

class BaseClassLoader extends Threaded implements ClassLoader{

	/** @var ClassLoader|null */
	private $parent;
	/** @var Threaded|string[] */
	private $lookup;
	/** @var Threaded|string[] */
	private $classes;

	public function __construct(ClassLoader $parent = null){
		$this->parent = $parent;
		$this->lookup = new Threaded();
		$this->classes = new Threaded();
	}

	/**
	 * Adds a path to the lookup list
	 *
	 * @param string $path
	 * @param bool   $prepend
	 *
	 * @return void
	 */
	public function addPath($path, $prepend = false){

		foreach($this->lookup as $p){
			if($p === $path){
				return;
			}
		}

		if($prepend){
			$this->lookup->synchronized(function(string $path) : void{
				$entries = $this->getAndRemoveLookupEntries();
				$this->lookup[] = $path;
				foreach($entries as $entry){
					$this->lookup[] = $entry;
				}
			}, $path);
		}else{
			$this->lookup[] = $path;
		}
	}

	/**
	 * @return string[]
	 */
	protected function getAndRemoveLookupEntries(){
		$entries = [];
		while($this->lookup->count() > 0){
			$entries[] = $this->lookup->shift();
		}
		return $entries;
	}

	/**
	 * Removes a path from the lookup list
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	public function removePath($path){
		foreach($this->lookup as $i => $p){
			if($p === $path){
				unset($this->lookup[$i]);
			}
		}
	}

	/**
	 * Returns an array of the classes loaded
	 *
	 * @return string[]
	 */
	public function getClasses(){
		$classes = [];
		foreach($this->classes as $class){
			$classes[] = $class;
		}
		return $classes;
	}

	/**
	 * Returns the parent ClassLoader, if any
	 *
	 * @return ClassLoader|null
	 */
	public function getParent(){
		return $this->parent;
	}

	/**
	 * Attaches the ClassLoader to the PHP runtime
	 *
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function register($prepend = false){
		return spl_autoload_register(function(string $name) : void{
			$this->loadClass($name);
		}, true, $prepend);
	}

	/**
	 * Called when there is a class to load
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function loadClass($name){
		$path = $this->findClass($name);
		if($path !== null){
			include($path);
			if(!class_exists($name, false) and !interface_exists($name, false) and !trait_exists($name, false)){
				return false;
			}

			if(method_exists($name, "onClassLoaded") and (new ReflectionClass($name))->getMethod("onClassLoaded")->isStatic()){
				$name::onClassLoaded();
			}

			$this->classes[] = $name;

			return true;
		}

		return false;
	}

	/**
	 * Returns the path for the class, if any
	 *
	 * @param string $name
	 *
	 * @return string|null
	 */
	public function findClass($name){
		$baseName = str_replace("\\", DIRECTORY_SEPARATOR, $name);

		foreach($this->lookup as $path){
			if(PHP_INT_SIZE === 8 and file_exists($path . DIRECTORY_SEPARATOR . $baseName . "__64bit.php")){
				return $path . DIRECTORY_SEPARATOR . $baseName . "__64bit.php";
			}elseif(PHP_INT_SIZE === 4 and file_exists($path . DIRECTORY_SEPARATOR . $baseName . "__32bit.php")){
				return $path . DIRECTORY_SEPARATOR . $baseName . "__32bit.php";
			}elseif(file_exists($path . DIRECTORY_SEPARATOR . $baseName . ".php")){
				return $path . DIRECTORY_SEPARATOR . $baseName . ".php";
			}
		}

		return null;
	}
}
