<?php

namespace Zz\BackupOMatic\Config;

use Symfony\Component\Yaml\Yaml;
use Zz\BackupOMatic\Exception\InvalidYamlFileException;
use Zz\BackupOMatic\Exception\InvalidYamlConfigException;

class YamlConfig extends Config
{
	public function __construct ( $yml ) {
		try {
            $config = Yaml::parse($yml);
        } catch ( \Symfony\Component\Yaml\Exception\ParseException $e ) {
        	throw new InvalidYamlFileException($e);
        }
        
        $this->validate($config);
		
		$files = $this->read($config['Files']);
		
		parent::__construct($files, (string)$config['Backup Directory']);
	}
	
	protected function read ( $nodeContent ) {
		$files = [];
		foreach ( $nodeContent as $child ) {
			switch ( true ) {
				case $this->isSimpleEntry($child) :
					$files[] = new File ((string)$child);
				break;
				case $this->isSingletonEntry($child) :
					$files[] = new File ((string)key($child), (string)current($child));
				break;
				case $this->isNodeEntry($child) :
					$childFiles = $this->read(current($child));
					foreach ( $childFiles as $childFile ) {
						$files[] = new File (key($child) . '/' . $childFile, $childFile->getAlt());
					}
				break;
				default:
					throw new \LogicException();
			}
		}
		
		return $files;
	}
	
	protected function validate ( array $config ) {
		$requiredKeys = [ 'Files', 'Backup Directory' ];
		foreach ( $requiredKeys as $key ) {
			if ( !array_key_exists($key, $config) ) {
				throw new InvalidYamlConfigException('the key "' . $key . '" is required');
			}
		}
		
		foreach ( array_keys($config) as $key ) {
			switch ( $key ) {
				case 'Files' :
					$this->validateNodeContent($config['Files']);
				break;
				case 'Backup Directory' :
					if ( !(
						is_string($config['Backup Directory'])
						|| is_int($config['Backup Directory'])
					)) {
						throw new InvalidYamlConfigException('the value "Backup Directory" has to be a string (or an int)');
					}
				break;
				default:
					throw new InvalidYamlConfigException('the key "' . $key . '" doesn\'t exist');
			}
		}
	}
	
	protected function validateNodeContent ( array $nodeContent ) {
		foreach ( $nodeContent as $child ) {
			if ( !(
				$this->isSimpleEntry($child)
				|| $this->isSingletonEntry($child)
				|| $this->isNodeEntry($child)
			)) {
				throw new InvalidYamlConfigException('malformed files hierarchy');
			}
			
			if ( $this->isNodeEntry($child) ) {
				$this->validateNodeContent(current($child));
			}
		}
	}
	
	protected function isSimpleEntry ( $simple ) {
		return is_string($simple) || is_int($simple);
	}
	
	protected function isSingletonEntry ( $singleton ) {
		return 	is_array($singleton) && count($singleton) === 1
				&& (is_string(key($singleton)) 		|| is_int(key($singleton)))
				&& (is_string(current($singleton)) 	|| is_int(current($singleton)))
		;
	}
	
	protected function isNodeEntry ( $node ) {
		return 	is_array($node) && (is_string(key($node)) || is_int(key($node)))
				&& is_array(current($node))
		;
	}
}
