<?php

namespace Zz\BackupOMatic\Config;

use Zz\BackupOMatic\Util\FileInfo;
use Zz\BackupOMatic\Exception\InvalidConfigException;

class File extends FileInfo
{
	protected $alt;
	
	public function __construct ( $path, $alt = null )
	{
		parent::__construct($path);
		if ( $alt !== null ) {
			$this->setAlt($alt);
		}
	}
	
	public function checkExists () {
		if ( !$this->exists() ) {
			throw new InvalidConfigException('The file "' . $this . '" doesn\'t exist');
		}
	}
	
	public function setAlt ( $alt )
	{
		if ( !is_string($alt) ) {
			throw new \InvalidArgumentException('$alt must be a string in File::setAlt');
		}
		$this->alt = $alt;
		return $this;
	}
	
	public function getAlt ()
	{
		return $this->alt;
	}
}
