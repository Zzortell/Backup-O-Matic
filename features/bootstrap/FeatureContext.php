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
     * @AfterScenario
     */
    public function removeCurrentDir()
    {
        $dir = getcwd();
        chdir('..');
        
        $this->utils->delete($dir);
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
     * @Given /^I have a folder "([^"]*)"$/
     */
    public function iHaveAFolder($folder)
    {
        if ( !file_exists($folder) ) {
            mkdir($folder);
        }
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
     * @Then /^I should get an error by running "([^"]*)"$/
     */
    public function iShouldGetAnErrorByRunning($command)
    {
        $error = false;
        try {
            $this->iRun($command);
        } catch ( \Exception $e ) {
            $error = true;
        }
        if ( !$error ) {
            throw new \Exception('None error has been thrown.');
        }
    }

    /**
     * @Then /^I should have a file "([^"]*)"$/
     */
    public function iShouldHaveAFile($file)
    {
        if ( !file_exists($file) ) {
            throw new \Exception('The file '.$file.' doesn\'t exist.');
        }
    }
    
    /**
     * @Then /^I should have a folder matching "([^"]*)"$/
     */
    public function iShouldHaveAFolderMatching($pattern)
    {
        $found = false;
        foreach ( scandir('.') as $file ) {
            if ( preg_match($pattern, $file) && is_dir($file) ) {
                $found = true;
            }
        }
        
        if ( !$found ) {
            throw new \Exception('None folder found, matching "'.$pattern.'".');
        }
    }
}
