<?php

namespace Zz\BackupOMatic\Exception;

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
	use ProblemReasonMsgFormattingTrait;
	
	protected $problem = 'The process has failed';
	
	public function __construct ( $reason ) {
		$this->_construct($reason);
	}
}
