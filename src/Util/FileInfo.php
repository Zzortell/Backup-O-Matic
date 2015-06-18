<?php

namespace Zz\BackupOMatic\Util;

class FileInfo extends \SplFileInfo
{
	/**
	 * Resolve parent dir even if the file doesn't exist
	 */
	public function getParentDir ()
	{
		$path = $this->getPathname();
		
		// Remove all /./
		$path = preg_replace('#(/?)(?<!\.)\.(?!\.)/?#', '$1', $path);
		
		// Remove a possible final /
		$path = preg_replace('#/$#', '', $path);
		
		// Remove all /..
		while ( strpos($path, '..') !== false ) {
			if ( strpos($path, '..') === 0 ) {
				$path = getcwd() . '/' . $path;
			}
			
			// Remove all /dir/..
			$path = preg_replace('#/?[^/\.]+/\.\.#', '', $path);
		}
		
		// If relative path and there is no parent dir
		if ( strpos($path, '/') === false ) {
			if ( $path !== '' ) {
				$path = getcwd() . '/.';
			} else {
				$path = getcwd();
			}
		}
		
		// Get parent dir
		$parentDir = preg_replace('#/[^/]+$#', '', $path);
		
		return new static ($parentDir);
	}
	
	public function exists ()
	{
		return file_exists($this);
	}
}
