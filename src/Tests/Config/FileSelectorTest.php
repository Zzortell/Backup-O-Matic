<?php

namespace Zz\BackupOMatic\Tests\Config;

use Zz\BackupOMatic\Config\FileSelector;
use Zz\BackupOMatic\Utils\FilesAndFolders;

class FileSelectorTest extends \PHPUnit_Framework_TestCase
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
        chdir('..');
        FilesAndFolders::delete($this->testDir);
    }
    
    /**
	 * @dataProvider parsePatternProvider
	 */
	public function testParsePattern ( $pattern, $expected )
	{
		$selector = new FileSelector ($pattern);
		$class = new \ReflectionClass($selector);
		$method = $class->getMethod('parsePattern');
		$method->setAccessible(true);
		$method->invokeArgs($selector, []);
		
		$this->assertEquals($expected, $selector->getPattern());
	}
	
	/**
	 * @dataProvider selectFilesProvider
	 */
	public function testSelectFiles ( $pattern, array $expected )
	{
		mkdir('a');
		mkdir('a/b');
		touch('a/b/b');
		touch('c');
		touch('#');
		mkdir('Testi');
		mkdir('Testi/cule');
		touch('Testi/cule/za');
		mkdir('Testi/a');
		mkdir('Testi/a/b');
		mkdir('Testi/#');
		mkdir('Testi/#/cule');
		mkdir('Testi/#/cule/nap');
		mkdir('Testi/#/cule/nap/8');
		mkdir('Testi/#/cule/nap/8*');
		mkdir('Testi/#/cule/nap/8hey!:#*');
		
		$selector = new FileSelector ($pattern);
		$files = [];
		foreach ( $selector->selectFiles() as $file ) {
			$files[] = $file->getPathname();
		}
		sort($expected);
		sort($files);
		$this->assertEquals($expected, $files);
	}
	
	public function parsePatternProvider ()
	{
		return $this->patchProviders([
			// Simple
			'a/b', '#', '\\', '\\', '*', '\\*',
			// Complex
			'#.+#',
			'#.+#',
			'#Testi/cule/.+#',
			'#Testi/\#/cule/.+/8\*#',
		]);
	}
	
	public function selectFilesProvider ()
	{
		return $this->patchProviders([
			// Simple
			[ 'a/b' ], [ '#' ], [ '\\' ], [ '\\' ], [ '*' ], [ '\\*' ],
			// Complex
			[ 'a', 'a/b', 'a/b/b', 'c', '#', 'Testi', 'Testi/cule', 'Testi/cule/za',
				'Testi/a', 'Testi/a/b', 'Testi/#', 'Testi/#/cule', 'Testi/#/cule/nap',
				'Testi/#/cule/nap/8', 'Testi/#/cule/nap/8*', 'Testi/#/cule/nap/8hey!:#*' ],
			[ 'a', 'a/b', 'a/b/b', 'c', '#', 'Testi', 'Testi/cule', 'Testi/cule/za',
				'Testi/a', 'Testi/a/b', 'Testi/#', 'Testi/#/cule', 'Testi/#/cule/nap',
				'Testi/#/cule/nap/8', 'Testi/#/cule/nap/8*', 'Testi/#/cule/nap/8hey!:#*' ],
			[ 'Testi/cule/za' ],
			[ 'Testi/#/cule/nap/8*' ],
		]);
	}
	
	protected function patternProvider ()
	{
		return [
			// Simple
			'a/b', '\#', '\\', '\\\\', '\*', '\\\*',
			// Complex
			'*',
			'#.+#',
			'Testi/cule/*',
			'Testi/\#/cule/*/8\*',
		];
	}
	
	protected function patchProviders ( array $provider2 )
	{
		$provider = [];
		foreach ( $this->patternProvider() as $key => $value ) {
			$provider[] = [ $value, $provider2[$key] ];
		}
		return $provider;
	}
}
