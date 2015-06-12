<?php

namespace Zz\BackupOMatic\Tests;

use Zz\BackupOMatic\BackupOMatic;
use Zz\BackupOMatic\Config\YamlConfig;
use Zz\BackupOMatic\Utils\FilesAndFolders;

class BackupOMaticTest extends \PHPUnit_Framework_TestCase
{
    protected $testDir = 'tests';
    protected function moveToTestDir ()
    {
    	if ( !file_exists($this->testDir) ) {
        	mkdir($this->testDir);
        }
        chdir($this->testDir);
    }
    protected function moveToRootDir ()
    {
    	chdir('..');
    }
    protected function cleanTestDir ()
    {
    	FilesAndFolders::delete($this->testDir);
    }
    
    /**
     * @dataProvider ymlProvider
     */
    public function test ( $yml )
    {
        $this->moveToTestDir();
        
        $config = new YamlConfig ($yml);
        foreach ( $config->getFiles() as $file ) {
        	touch($file->getPath());
        }
    	
        $backupOMatic = new BackupOMatic;
        $backupOMatic->backup($config);
        
        foreach ( $config->getFiles() as $file ) {
        	$this->assertTrue(file_exists($config->getDir() . DIRECTORY_SEPARATOR . $file->getPath()));
        }
        
        $this->moveToRootDir();
        $this->cleanTestDir();
    }
    
    public function ymlProvider ()
    {
    	return [
    		[<<<'YML'
Files:
    - file
Backup Directory: backup
YML
			],
    	];
    }
    
    /**
     * @dataProvider longYmlProvider
     * @group long
     */
    public function testHeavy ( $yml ) {
    	$this->moveToTestDir();
    	
    	$config = new YamlConfig ($yml);
        foreach ( $config->getFiles() as $file ) {
        	file_put_contents($file->getPath(), str_pad('', pow(2, 30), 'X'));
        }
        
        $this->moveToRootDir();
    	$this->test($yml);
    }
    
    public function longYmlProvider ()
    {
    	return [
    		[<<<'YML'
Files:
    - file
Backup Directory: backup
YML
			],
    	];
    }
}
