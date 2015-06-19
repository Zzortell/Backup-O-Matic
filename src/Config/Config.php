<?php

namespace Zz\BackupOMatic\Config;

class Config
{
	protected $files = [];
	protected $dir;
	
	public function __construct ( array $files, $dir ) {
		foreach ( $files as $file ) {
			if ( $file instanceof File ) {
				$this->files[] = $file;
			} elseif ( $file instanceof FileSelector ) {
				$this->files = array_merge($this->files, $file->selectFiles());
			} else {
				throw new \InvalidArgumentException(
					'$files must be instances of Zz\BackupOMatic\Config\File '
					. 'or Zz\BackupOMatic\Config\FileSelector in ' . __METHOD__
				);
			}
		}
		
		if ( !is_string($dir) ) {
			throw new \InvalidArgumentException('$dir must be a string in ' . __METHOD__);
		}
		
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
