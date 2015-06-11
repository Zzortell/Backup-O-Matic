<?php

namespace Zz\BackupOMatic;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Zz\BackupOMatic\Event\BackupOMaticProgressEvent;

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
	public function backup ( Config\Config $config )
	{
		$config->checkFilesExist();
		
		@mkdir($config->getDir(), 0777, true);
		foreach ( $config->getFiles() as $file ) {
			if ( !copy($file->getPath(), $config->getDir() . DIRECTORY_SEPARATOR . $file->getPath()) ) {
				throw new \Exception();
			}
			$event = new BackupOMaticProgressEvent (key($file), $file);
			$this->dispatcher->dispatch(self::PROGRESS_EVENT, $event);
		}
	}
}
