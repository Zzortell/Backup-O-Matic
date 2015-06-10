<?php

namespace Zz\BackupOMatic\Config;

use Symfony\Component\Yaml\Yaml;
use Zz\BackupOMatic\Exception\InvalidYamlConfigException;

class YamlConfig extends Config
{
	public function __construct ( $yml ) {
		try {
            $config = Yaml::parse($yml);
        } catch ( \Symfony\Component\Yaml\Exception\ParseException $e ) {
        	throw new InvalidYamlConfigException('The Yaml file contains errors:' . PHP_EOL . "\t" . $e->getMessage());
        } catch ( \Exception $e ) {
            throw new \InvalidArgumentException('Unable to parse the Yaml file.', null, $e);
        }
        
        $this->validateNodeContent($config['Files']);
		
		$config['Files'] = $this->format($config['Files']);
		
		parent::__construct($config);
	}
	
	protected function format ( $nodeContent ) {
		$formatted = [];
		foreach ( $nodeContent as $child ) {
			switch ( true ) {
				case $this->isSingleEntry($child) :
					$formatted[] = $child;
				break;
				case $this->isSingletonEntry($child) :
					$formatted[key($child)] = current($child);
				break;
				case $this->isNodeEntry($child) :
					$parent = key($child) . '/';
					$child[key($child)] = $this->format(current($child));
					foreach ( current($child) as $key => $value ) {
						switch ( true ) {
							case $this->isSimpleEntry($key, $value) :
								$formatted[] = $parent . $value;
							break;
							case $this->isComplexEntry($key, $value) :
								$formatted[$parent.$key] = $value;
							break;
							default:
								throw new \LogicException();
						}
					}
				break;
				default:
					throw new \LogicException();
					
			}
		}
		
		return $formatted;
	}
	
	protected function validateNodeContent ( Array $nodeContent ) {
		foreach ( $nodeContent as $child ) {
			if ( !(
				$this->isSingleEntry($child)
				|| $this->isSingletonEntry($child)
				|| $this->isNodeEntry($child)
			)) {
				throw new InvalidYamlConfigException('The Yaml config is not valid: malformed files hierarchy.');
			}
			
			if ( $this->isNodeEntry($child) ) {
				$this->validateNodeContent(current($child));
			}
		}
	}
	
	protected function isSingleEntry ( $single ) {
		return is_string($single);
	}
	
	protected function isSingletonEntry ( $singleton ) {
		return is_array($singleton) && count($singleton) === 1
				&& is_string(key($singleton)) && is_string(current($singleton));
	}
	
	protected function isNodeEntry ( $node ) {
		return is_array($node) && is_string(key($node)) && is_array(current($node));
	}
}
