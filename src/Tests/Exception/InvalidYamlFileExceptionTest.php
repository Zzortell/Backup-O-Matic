<?php

namespace Zz\BackupOMatic\Tests\Exception;

use Zz\BackupOMatic\Exception\InvalidYamlFileException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class InvalidYamlFileExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException               Zz\BackupOMatic\Exception\InvalidYamlFileException
     * @expectedExceptionMessageRegExp  #Invalid Yaml file:\n\t.+#
     */
    public function test ()
    {
        try {
            Yaml::parse("\t");
        } catch ( ParseException $e ) {
            $e = new InvalidYamlFileException ($e);
            throw $e;
        }
    }
}
