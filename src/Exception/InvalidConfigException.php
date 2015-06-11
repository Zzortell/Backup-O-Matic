<?php

namespace Zz\BackupOMatic\Exception;

class InvalidConfigException extends InvalidArgumentException
{
	protected $problem = 'The config is not valid';
}
