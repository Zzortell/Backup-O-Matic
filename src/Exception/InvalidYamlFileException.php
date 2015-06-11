<?php

namespace Zz\BackupOMatic\Exception;

use Symfony\Component\Yaml\Exception\ParseException;

class InvalidYamlFileException extends InvalidArgumentException
{
	protected $parseException;
	protected $problem = 'Invalid Yaml file';
	
	public function __construct ( ParseException $parseException )
	{
		$this->setParseException($parseException);
		parent::__construct('');
	}
	
	public function setParseException ( ParseException $parseException )
	{
		$this->parseException = $parseException;
	}
	
	protected function setMessage ()
	{
		$this->message = $this->problem . ':' . PHP_EOL . "\t" . $this->parseException->getMessage();
	}
}
