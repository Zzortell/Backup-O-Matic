<?php

namespace Zz\BackupOMatic;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Zz\BackupOMatic\Event\BackupOMaticProgressEvent;
use Zz\BackupOMatic\Config\Config;
use Zz\BackupOMatic\Exception\FailureCopyingException;

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
			$toRead = $file->getPath();
			$toWrite = $config->getDir() . DIRECTORY_SEPARATOR . $file->getPath();
			
			if ( !is_readable($toRead) ) {
				throw new FailureCopyingException('The file "' . $toRead . '" is not readable');
			}
			
			if ( file_exists($toWrite) ) {
				if ( !is_writable($toWrite) ) {
					throw new FailureCopyingException('The file "' . $toWrite . '" is not writable');
				}
			} else {
				$dirToWrite = dirname($toWrite);
				while ( !file_exists($dirToWrite) ) {
					$dirToWrite = dirname($dirToWrite);
				}
				
				if ( !is_writable($dirToWrite) ) {
					throw new FailureCopyingException('The folder "' . $dirToWrite . '" is not writable');
				}
				
				if ( !file_exists(dirname($toWrite)) ) {
					mkdir(dirname($toWrite), 0777, true);
				}
			}
			
			if ( !copy($toRead, $toWrite) ) {
				throw new FailureCopyingException('An issue happened');
			}
			
			$event = new BackupOMaticProgressEvent (key($file), $file);
			$this->dispatcher->dispatch(self::PROGRESS_EVENT, $event);
		}
	}
}
