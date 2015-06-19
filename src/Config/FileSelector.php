<?php

namespace Zz\BackupOMatic\Config;

use Zz\BackupOMatic\Exception\InvalidConfigException;

/**
 * A smart file selector based on complex patterns
 *
 * Usage:
 *  	#pattern# 	RegExp
 *  	*			= #.+#
 */
class FileSelector
{
	protected $selector;
	protected $pattern;
	protected $isComplex = true;
	protected $alt;
	
	public function __construct ( $selector, $alt = null )
	{
		$this->setSelector($selector);
		$this->setAlt($alt);
	}
	
	public function selectFiles ()
	{
		$this->parsePattern();
		
		if ( !$this->isComplex ) {
			return [ new File ($this->getPattern(), $this->getAlt()) ];
		}
		
		// Search not complex parent dir
		preg_match('#^[^\#*]/#', $this->getPattern(), $matches);
		if ( count($matches) === 1 ) {
			$dir = $matches[0];
		} else {
			$dir = '.';
		}
		
		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator ($dir, \FilesystemIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST
		);
		
		$files = [];
		foreach ( $iterator as $child ) {
			if ( preg_match($this->getPattern(), $child) ) {
				$files[] = new File (str_replace('./', '', $child), $this->getAlt());
			}
		}
		
		return $files;
	}
	
	protected function parsePattern ()
	{
		// If it's not a complex selector (which contains # or * not preceded by \)
		if ( !preg_match('#(?<!\\\\)[\#*]#', $this->selector) ) {
			$this->isComplex = false;
			// Unescape # * \
			$this->pattern = preg_replace('#\\\\([\#*\\\\])#', '$1', $this->selector);
			return;
		}
		
		// Accept plain RegExp
		if ( preg_match('/^#.+#$/', $this->selector) ) {
			$this->pattern = $this->selector;
			return;
		}
		
		// Escape special char
		$pattern = preg_quote($this->selector, '#');

		// Search \* not preceded by \\ and replace by .+
		$pattern = preg_replace('#(?<!\\\\\\\\)\\\\\\*#', '.+', $pattern);
		
		//Unescape: \\\# \\\* \\\\ are replaced by \# \* \\
		$pattern = preg_replace('#\\\\\\\\(\\\\[\#*\\\\])#', '$1', $pattern);
					
		// Set delimiters
		$pattern = '#' . $pattern . '#';
		
		$this->pattern = $pattern;
	}
	
	public function setSelector ( $selector )
	{
		if ( !is_string($selector) ) {
			throw new \InvalidArgumentException('$selector must be a string in ' . __METHOD__);
		}
		$this->selector = $selector;
	}
	
	public function getSelector ()
	{
		return $this->selector;
	}
	
	public function getPattern ()
	{
		return $this->pattern;
	}
	
	public function __toString ()
	{
		return $this->getSelector();
	}
	
	public function setAlt( $alt )
	{
		$this->alt = $alt;
	}
	
	public function getAlt()
	{
		return $this->alt;
	}
}
