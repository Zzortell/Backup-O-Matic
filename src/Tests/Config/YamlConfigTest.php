<?php

namespace Zz\BackupOMatic\Tests\Config;

use Zz\BackupOMatic\Config\YamlConfig;

class YamlConfigTest extends \PHPUnit_Framework_TestCase
{
	/**
     * @dataProvider ymlProvider
     */
    public function testYamlConfig ( $yml, array $filesResult, $dirResult )
	{
		$config = new YamlConfig($yml);
        
        $files = [];
        foreach ( $config->getFiles() as $file ) {
            $files[$file->getPath()] = $file->getAlt();
        }
        $this->assertEquals($files, $filesResult);
		$this->assertEquals($config->getDir(), $dirResult);
	}
    
    public function ymlProvider ()
    {
        return [
            [
                <<<'YML'
Files:
    - superFile
    - otherFile: alt
Backup Directory: testsDir
YML
                ,
                [ 'superFile' => null, 'otherFile' => 'alt' ],
                'testsDir'
            ],
            
            [
                <<<'YML'
Files:
    - folder:
        - superFile
        - otherFile: alt
Backup Directory: dir
YML
                ,
                [ 'folder/superFile' => null, 'folder/otherFile' => 'alt' ],
                'dir'
            ],
            
            [
                <<<'YML'
Files:
    - folder:
        - superFile
        - otherFile: alt
    - folder2:
        - superFile
        - otherFile: alt
Backup Directory: dir
YML
                ,
                [
                    'folder/superFile' => null,
                    'folder/otherFile' => 'alt',
                    'folder2/superFile' => null,
                    'folder2/otherFile' => 'alt'
                ],
                'dir'
            ],
            
            [
                <<<'YML'
Files:
    - complex: Complex
    - simple
    - folder:
        - superFile
        - otherFile: alt
    - superfolder2:
        - superFile
        - otherFile: alt
        - folder3:
            - folder
#           - 42: poc
            - time
Backup Directory: dir
YML
                ,
                [
                    'complex' => 'Complex',
                    'simple' => null,
                    'folder/superFile' => null,
                    'folder/otherFile' => 'alt',
                    'superfolder2/superFile' => null,
                    'superfolder2/otherFile' => 'alt',
                    'superfolder2/folder3/folder' => null,
                    // 'superfolder2/folder3/42' => poc,
                    'superfolder2/folder3/time' => null,
                ],
                'dir'
            ],
        ];
    }
}
