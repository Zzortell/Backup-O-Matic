<?php

namespace Zz\BackupOMatic\Config;

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
