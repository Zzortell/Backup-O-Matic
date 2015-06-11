<?php

namespace Zz\BackupOMatic\Config;

class Config
{
	protected $files = [];
	protected $dir;
	
	public function __construct ( array $files, $dir ) {
		foreach ( $files as $file ) {
			if ( ! $file instanceof File ) {
				throw new \InvalidArgumentException('$files must be instances of Zz\BackupOMatic\Config\File in Config::__construct');
			}
		}
		
		if ( !is_string($dir) ) {
			throw new \InvalidArgumentException('$dir must be a string in Config::__construct');
		}
		
		$this->files = $files;
		$this->dir = $dir;
	}
	
	public function checkFilesExist () {
		foreach ( $this->getFiles() as $file ) {
			$file->checkExists();
		}
	}
	
	public function getFiles () {
		return $this->files;
	}
	
	public function getDir () {
		return $this->dir;
	}
}
