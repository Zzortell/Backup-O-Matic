<?php

namespace Zz\BackupOMatic\Config;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Zz\BackupOMatic\Exception\InvalidConfigException;

class Config
{
	protected $files = [];
	protected $dir;
	
	public function __construct ( Array $config ) {
		$validator = Validation::createValidator();
		$violations = $validator->validate($config, new Assert\Collection([
			'fields' => [
				'Files' => new Assert\All([
					new Assert\Type([
						'type' 	  => 'string',
						'message' => 'Files should be strings.'
					])
				]),
				'Backup Directory' => new Assert\Type([
					'type' 	  => 'string',
					'message' => 'The Backup Directory should be a string.'
				])
			],
			'allowMissingFields' => false,
			'allowExtraFields' 	 => false
		]));
		
		if ( count($violations) !== 0 ) {
			$message = 'The config is not valid. Errors:';
			foreach ( $violations as $violation ) {
				$message .= PHP_EOL . "\t" . '- in ' . $violation->getPropertyPath() . ': '
							. $violation->getMessage();
			}
			
			throw new InvalidConfigException($message);
		}
		
		foreach ( $config['Files'] as $key => $value ) {
			if ( $this->isSimpleEntry($key, $value) ) {
				$this->addFile(new File ($value));
			} elseif ( $this->isComplexEntry($key, $value) ) {
				$this->addFile(new File ($key, $value));
			}
		}
		$this->setDir($config['Backup Directory']);
	}
	
	protected function isSimpleEntry ( $key, $value ) {
		if ( is_int($key) && is_string($value) ) {
			return true;
		}
		return false;
	}
	
	protected function isComplexEntry ( $key, $value ) {
		if ( is_string($key) && is_string($value) ) {
			return true;
		}
		return false;
	}
	
	public function addFile ( File $file ) {
		$this->files[] = $file;
		return $this;
	}
	
	public function setDir ( $dir ) {
		if ( !is_string($dir) ) {
			throw new \InvalidArgumentException('$dir must be a string in Config::setDir');
		}
		$this->dir = $dir;
		return $this;
	}
	
	public function getFiles () {
		return $this->files;
	}
	
	public function getDir () {
		return $this->dir;
	}
}
