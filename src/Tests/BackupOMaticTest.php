<?php

namespace Zz\BackupOMatic\Tests;

use Zz\BackupOMatic\BackupOMatic;
use Zz\BackupOMatic\Config\YamlConfig;
use Zz\BackupOMatic\Utils\FilesAndFolders;

class BackupOMaticTest extends \PHPUnit_Framework_TestCase
{
    protected $testDir = 'tests';
    
    public function setUp ()
    {
        if ( !file_exists($this->testDir) ) {
            mkdir($this->testDir);
        }
        chdir($this->testDir);
    }
    
    public function tearDown ()
    {
        chmod('.', 0777);
        chdir('..');
        FilesAndFolders::delete($this->testDir);
    }
    
    /**
     * @dataProvider ymlProvider
     */
    public function testFilesBackup ( $yml )
    {
        $config = new YamlConfig ($yml);
        foreach ( $config->getFiles() as $file ) {
            $dir = $file->getParentDir();
            if ( !$dir->exists() ) {
                mkdir($dir, 0777, true);
            }
        	touch($file);
        }
    	
        $backupOMatic = new BackupOMatic;
        $backupOMatic->backup($config);
        
        foreach ( $config->getFiles() as $file ) {
        	$this->assertTrue(file_exists($config->getDir() . '/' . $file));
        }
    }
    
    /**
     * @dataProvider ymlProvider
     */
    public function testFolderBackup ( $yml )
    {
        $config = new YamlConfig ($yml);
        foreach ( $config->getFiles() as $folder ) {
            $dir = $folder->getParentDir();
            if ( !$dir->exists() ) {
                mkdir($dir, 0777, true);
            }
            if ( !$folder->exists() ) {
                mkdir($folder);
            }
            touch($folder . '/a');
            touch($folder . '/b');
        }
        
        $backupOMatic = new BackupOMatic;
        $backupOMatic->backup($config);
        
        foreach ( $config->getFiles() as $folder ) {
            $this->assertTrue(file_exists($config->getDir() . '/' . $folder . '/a'));
            $this->assertTrue(file_exists($config->getDir() . '/' . $folder . '/b'));
        }
    }
    
    public function ymlProvider ()
    {
        return [
            [<<<'YML'
Files:
    - file
    - folder/folder:
        - file
Backup Directory: backup
YML
            ],
        ];
    }
    
    /**
     * @expectedException               Zz\BackupOMatic\Exception\FailureCopyingException
     * @expectedExceptionMessageRegExp  #The file ".+" is not readable#
     */
    public function testFileNotReadableHandling ()
    {
        $config = new YamlConfig (<<<'YML'
Files:
    - file
Backup Directory: backup
YML
        );
        $backupOMatic = new BackupOMatic;
        
        touch('file');
        chmod('file', 0222);
        
        $backupOMatic->backup($config);
    }
    
    /**
     * @expectedException               Zz\BackupOMatic\Exception\FailureCopyingException
     * @expectedExceptionMessageRegExp  #The folder ".+" is not writable#
     */
    public function testFolderNotWritableHandling ()
    {
        $config = new YamlConfig (<<<'YML'
Files:
    - file
Backup Directory: backup
YML
        );
        $backupOMatic = new BackupOMatic;
        
        touch('file');
        chmod('.', 0555);
        
        $backupOMatic->backup($config);
    }
    
    /**
     * @expectedException               Zz\BackupOMatic\Exception\FailureCopyingException
     * @expectedExceptionMessageRegExp  #The file ".+" is not writable#
     */
    public function testFileNotWritableHandling ()
    {
        $config = new YamlConfig (<<<'YML'
Files:
    - file
Backup Directory: backup
YML
        );
        $backupOMatic = new BackupOMatic;
        
        touch('file');
        mkdir('backup');
        touch('backup/file');
        chmod('backup/file', 0555);
        
        $backupOMatic->backup($config);
    }
    
    /**
     * @group long
     */
    public function testHeavy () {
        $yml = <<<'YML'
Files:
    - file
Backup Directory: backup
YML;
    	$config = new YamlConfig ($yml);
        foreach ( $config->getFiles() as $file ) {
        	file_put_contents($file, str_pad('', pow(2, 30), 'X'));
        }
        
    	$this->test($yml);
    }
}
