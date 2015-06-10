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
            $files[] = [ $file->getPath(), $file->getAlt() ];
        }
        $this->assertEquals($filesResult, $files);
		$this->assertEquals($dirResult, $config->getDir());
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
                [ [ 'superFile', null ], [ 'otherFile', 'alt' ] ],
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
                [ [ 'folder/superFile', null ], [ 'folder/otherFile', 'alt' ] ],
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
                    [ 'folder/superFile',  null  ],
                    [ 'folder/otherFile',  'alt' ],
                    [ 'folder2/superFile', null  ],
                    [ 'folder2/otherFile', 'alt' ]
                ],
                'dir'
            ],
            
            [
                <<<'YML'
Files:
    - 42
    - 42: 7
    - 7:
        - 7
        - 42: 42
Backup Directory: 42
YML
                ,
                [
                    [ '42',    null ],
                    [ '42',    '7'  ],
                    [ '7/7',   null ],
                    [ '7/42',  '42' ],
                ],
                '42'
            ],
            
            [
                <<<'YML'
Files:
    - complex: ic
    - simple
    - folder:
        - superFile
        - otherFile: alt
    - superfolder2:
        - superFile
        - otherFile: alt
        - folder3:
            - folder
            - 42: poc
            - time
Backup Directory: dir
YML
                ,
                [
                    [ 'complex', 'ic' ],
                    [ 'simple',  null ],
                    [ 'folder/superFile', null  ],
                    [ 'folder/otherFile', 'alt' ],
                    [ 'superfolder2/superFile',         null    ],
                    [ 'superfolder2/otherFile',         'alt'   ],
                    [ 'superfolder2/folder3/folder',    null    ],
                    [ 'superfolder2/folder3/42',        'poc'   ],
                    [ 'superfolder2/folder3/time',      null    ],
                ],
                'dir'
            ],
        ];
    }
}
