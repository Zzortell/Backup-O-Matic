<?php

namespace Zz\BackupOMatic\Exception;

class FailureCopyingException extends RuntimeException
{
	protected $problem = 'The copy has failed';
}
