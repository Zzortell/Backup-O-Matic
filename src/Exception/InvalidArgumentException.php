<?php

namespace Zz\BackupOMatic\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
	use ProblemReasonMsgFormattingTrait;
	
	protected $problem = 'The argument is invalid';
	
	public function __construct ( $reason ) {
		$this->_construct($reason);
	}
}
