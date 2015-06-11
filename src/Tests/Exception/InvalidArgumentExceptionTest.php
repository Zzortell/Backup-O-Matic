<?php

namespace Zz\BackupOMatic\Tests\Exception;

use Zz\BackupOMatic\Exception\InvalidArgumentException;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        Zz\BackupOMatic\Exception\InvalidArgumentException
     * @expectedExceptionMessage The argument is invalid: tests.
     */
    public function test ()
    {
        $e = new InvalidArgumentException ('tests');
        throw $e;
    }
    
    /**
     * @expectedException        Zz\BackupOMatic\Exception\InvalidArgumentException
     * @expectedExceptionMessage Tests: tests.
     */
    public function testProblem ()
	{
		$e = new InvalidArgumentException ('tests');
        $e->setProblem('Tests');
		throw $e;
	}
}
