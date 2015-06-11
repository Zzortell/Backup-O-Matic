<?php

namespace Zz\BackupOMatic\Config;

use Zz\BackupOMatic\Exception\InvalidConfigException;

class File
{
	protected $path;
	protected $alt;
	
	public function __construct ( $path, $alt = null )
	{
		$this->setPath($path);
		if ( $alt !== null ) {
			$this->setAlt($alt);
		}
	}
	
	public function checkExists () {
		if ( !file_exists($this->getPath()) ) {
			throw new InvalidConfigException('The file "' . $this->getPath() . '" doesn\'t exist');
		}
	}
	
	public function setPath ( $path )
	{
		if ( !is_string($path) ) {
			throw new \InvalidArgumentException('$path must be a string in File::setPath');
		}
		$this->path = $path;
		return $this;
	}
	
	public function setAlt ( $alt )
	{
		if ( !is_string($alt) ) {
			throw new \InvalidArgumentException('$alt must be a string in File::setAlt');
		}
		$this->alt = $alt;
		return $this;
	}
	
	public function getPath ()
	{
		return $this->path;
	}
	
	public function getAlt ()
	{
		return $this->alt;
	}
}
