<?php

namespace Zz\BackupOMatic\Utils;

class FilesAndFolders
{
	/**
	 * Delete all files and folders
	 */
	public static function delete ( $path )
	{
		if ( is_dir($path) ) {
			$files = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ( $files as $file ) {
				if ( !in_array($file->getBasename(), array('.', '..')) ) {
					if ( $file->isDir() ) {
						rmdir($file);
					} elseif ( $file->isFile() || $file->isLink() ) {
						unlink($file);
					}
				}
			}

			return rmdir($path);
		} elseif ( is_file($path) || is_link($path) ) {
			return unlink($path);
		}

		return false;
	}
}
