<?php

namespace Zz\BackupOMatic\Tests\Config;

use Zz\BackupOMatic\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	/**
     * @dataProvider configProvider
     */
    public function testConfig ( array $config, array $filesResult, $dirResult )
	{
		$config = new Config($config);
        
        $files = [];
        foreach ( $config->getFiles() as $file ) {
            $files[$file->getPath()] = $file->getAlt();
        }
        $this->assertEquals($files, $filesResult);
		$this->assertEquals($config->getDir(), $dirResult);
	}
    
    public function configProvider ()
    {
        return [
            [
                [
                    'Files' => [
                        'superFile',
                        'otherFile' => 'alt'
                    ],
                    'Backup Directory' => 'testsDir'
                ],
                [ 'superFile' => null, 'otherFile' => 'alt' ],
                'testsDir'
            ],
        ];
    }
}
