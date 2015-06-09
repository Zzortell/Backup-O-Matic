<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
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
    public function iHaveAFile($file)
    {
        touch($file);
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
     * @Then /^Remove dir "([^"]*)"$/
     */
    public function removeDir($dir)
    {
        rmdir($dir);
    }
}
