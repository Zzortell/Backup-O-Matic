<?php

require_once 'vendor/autoload.php';

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Zz\BackupOMatic\Utils\FilesAndFolders as Utils;

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    protected $utils;
    
    public function __construct () {
        $this->utils = new Utils;
    }
    
    /**
     * @Given /^I am in "([^"]*)"$/
     */
    public function iAmIn($dir)
    {
        if ( !file_exists($dir) ) {
            mkdir($dir);
        }
        chdir($dir);
    }

    /**
     * @Given /^I have a file "([^"]*)"$/
     */
    public function iHaveAFile($filename)
    {
        touch($filename);
    }

    /**
     * @Given /^I have a Yaml config file:$/
     */
    public function iHaveAYamlConfigFile(PyStringNode $yml)
    {
        $file = fopen('backup.yml', 'w');
        fwrite($file, $yml);
    }

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun($command)
    {
        exec($command);
    }

    /**
     * @Then /^I should have a file "([^"]*)"$/
     */
    public function iShouldHaveAFile($file)
    {
        if ( !file_exists($file) ) {
            throw new Exception('The file '.$file.' doesn\'t exist.');
        }
    }
    
    /**
     * @Then /^Remove current dir$/
     */
    public function removeCurrentDir()
    {
        $dir = getcwd();
        chdir('..');
        
        $this->utils->delete($dir);
    }
}
