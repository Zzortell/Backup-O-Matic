<?php

namespace Zz\BackupOMatic\Event;

use Symfony\Component\EventDispatcher\Event;
use Zz\BackupOMatic\Config\File;

class BackupOMaticProgressEvent extends Event
{
	protected $step;
	protected $file;
	
	public function __construct ( $step, File $file )
	{
		$this->setStep($step);
		$this->setFile($file);
	}
	
	public function setStep ( $step )
	{
		$this->step = (int) $step;
	}
	
	public function setFile ( File $file )
	{
		$this->file = $file;
	}
	
	public function getStep ()
	{
		return $this->step;
	}
	
	public function getFile ()
	{
		return $this->file;
	}
}
