<?php

namespace Zz\BackupOMatic\Exception;

use Symfony\Component\Yaml\Exception\ParseException;

class InvalidYamlFileException extends \InvalidArgumentException implements ExceptionInterface
{
	protected $parse_exception;
	
	public function __construct ( ParseException $parse_exception )
	{
		$this->parse_exception($parse_exception);
		parent::__construct('Invalid Yaml file:' . PHP_EOL . "\t" . $this->parse_exception->getMessage());
	}
}
