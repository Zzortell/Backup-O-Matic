<?php

namespace Zz\BackupOMatic\Exception;

class InvalidYamlConfigException extends InvalidConfigException
{
	protected $problem = 'The Yaml config is not valid';
}
