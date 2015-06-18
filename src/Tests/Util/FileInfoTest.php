<?php

namespace Zz\BackupOMatic\Tests\Util;

use Zz\BackupOMatic\Util\FileInfo;
use Zz\BackupOMatic\Utils\FilesAndFolders;

class FileInfoTest extends \PHPUnit_Framework_TestCase
{
    public function setUp ()
    {
        if ( !file_exists('tests') ) {
            mkdir('tests');
        }
        if ( !file_exists('tests/tests') ) {
            mkdir('tests/tests');
        }
        chdir('tests/tests');
    }
    
    public function tearDown ()
    {
        chdir('../..');
        FilesAndFolders::delete('tests');
    }
    
    /**
     * @dataProvider parentDirProvider
     */
    public function testGetParentDir ( $path, $parentDir )
    {
        $this->assertEquals($parentDir, (string)(New FileInfo ($path))->getParentDir());
    }
    
    public function parentDirProvider ()
    {
        return [
            [ 'dir/file', 'dir' ],
            [ 'dir/../file', '' ],
            [ 'dir/dir2/../file', 'dir' ],
            [ '../../dir/dir2/../file', getcwd() . '/dir' ],
            [ '.././../dir/././dir2/.././file/.', getcwd() . '/dir' ],
            [ '.', getcwd() . '/tests' ],
            [ '', getcwd() . '/tests' ],
            [ '../a', getcwd() . '/tests' ],
            [ '..', getcwd() ],
        ];
    }
}
