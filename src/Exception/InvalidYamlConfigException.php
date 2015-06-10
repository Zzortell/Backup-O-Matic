<?php

namespace Zz\BackupOMatic\Exception;

class InvalidYamlConfigException extends \InvalidArgumentException implements ExceptionInterface
{
	protected $reason;
	
	public function __construct ( $reason )
	{
		if ( !is_string($reason) ) {
			throw new \InvalidArgumentException('$reason must be a string in InvalidYamlConfigException::__construct');
		}
		
		$this->reason = $reason;
		parent::__construct('The Yaml config is not valid: ' . $this->reason . '.');
	}
}
