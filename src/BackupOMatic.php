<?php

namespace Zz\BackupOMatic;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Zz\BackupOMatic\Event\BackupOMaticProgressEvent;
use Zz\BackupOMatic\Exception\FailureCopyingException;
use Zz\BackupOMatic\Config\Config;
use Zz\BackupOMatic\Config\File;
use Zz\BackupOMatic\Util\FileInfo;

class BackupOMatic
{
	const PROGRESS_EVENT = 'backupomatic.progress';
	
	protected $dispatcher;
	
	public function __construct ()
	{
		$this->dispatcher = new EventDispatcher;
	}
	
	public function getDispatcher ()
	{
		return $this->dispatcher;
	}
	
	/**
	 * Back up your files
	 */
	public function backup ( Config $config )
	{
		$config->checkFilesExist();
		
		foreach ( $config->getFiles() as $file ) {
			if ( $file->isDir() ) {
				if ( !$file->isReadable() ) {
					throw new FailureCopyingException('The folder "' . $file . '" is not readable');
				}
				if ( !$file->isExecutable() ) {
					throw new FailureCopyingException('The folder "' . $file . '" is not executable');
				}
				
				$iterator = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator ($file, \FilesystemIterator::SKIP_DOTS)
				);
				
				foreach ( $iterator as $child ) {
					$this->backupFile(new File ($child), $config->getDir());
				}
			} else {
				$this->backupFile($file, $config->getDir());
			}
			
			$event = new BackupOMaticProgressEvent (key($file), $file);
			$this->dispatcher->dispatch(self::PROGRESS_EVENT, $event);
		}
	}
	
	protected function backupFile ( File $file, $dir )
	{
		$toWrite = new FileInfo ($dir . '/' . $file);
		
		if ( !$file->isReadable() ) {
			throw new FailureCopyingException('The file "' . $file . '" is not readable');
		}
		
		if ( $toWrite->exists() ) {
			if ( !$toWrite->isWritable() ) {
				throw new FailureCopyingException('The file "' . $toWrite . '" is not writable');
			}
		} else {
			$dirToWrite = $toWrite->getParentDir();
			while ( !$dirToWrite->exists() ) {
				$dirToWrite = $dirToWrite->getParentDir();
			}
			
			if ( !$dirToWrite->isWritable() ) {
				throw new FailureCopyingException('The folder "' . $dirToWrite . '" is not writable');
			}
			
			if ( !$toWrite->getParentDir()->exists() ) {
				mkdir($toWrite->getParentDir(), 0777, true);
			}
		}
		
		if ( !copy($file, $toWrite) ) {
			throw new FailureCopyingException('An issue happened');
		}
	}
}
