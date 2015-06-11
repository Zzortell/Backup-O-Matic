<?php

namespace Zz\BackupOMatic\Tests\Exception;

use Zz\BackupOMatic\Exception\RuntimeException;

class RuntimeExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        Zz\BackupOMatic\Exception\RuntimeException
     * @expectedExceptionMessage The process has failed: tests.
     */
    public function test ()
    {
        $e = new RuntimeException ('tests');
        throw $e;
    }
    
    /**
     * @expectedException        Zz\BackupOMatic\Exception\RuntimeException
     * @expectedExceptionMessage Tests: tests.
     */
    public function testProblem ()
	{
		$e = new RuntimeException ('tests');
        $e->setProblem('Tests');
		throw $e;
	}
}
