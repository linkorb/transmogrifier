<?php

namespace LinkORB\Transmogrifier\BehatExtension;

use Behat\Behat\Context\BehatContext;
use LinkORB\Transmogrifier\Dataset;
use LinkORB\Transmogrifier\Database;

class TransmogrifierContext extends BehatContext
{

    /**
     * Path to dataset files
     * @var string
     */
    private $transmogrifier_dataset_path;

    /**
     * Path to database .conf files
     * @var string
     */
    private $transmogrifier_dbconf_path;

    /**
     * The database handle
     * 
     * @var Database
     */
    private $db;


    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        //print_r($parameters); exit('asdas');
        $this->transmogrifier_dataset_path = __DIR__ . "/../../../../";
        if (isset($parameters['transmogrifier_dataset_path'])) {
            $this->transmogrifier_dataset_path = $parameters['transmogrifier_dataset_path'];
        }

        if (isset($parameters['transmogrifier_dbconf_path'])) {
            $this->transmogrifier_dbconf_path = $parameters['transmogrifier_dbconf_path'];
        }
        //exit();
    }

    /**
     * @Given /^transmogrifier is enabled$/
     */
    public function transmogrifierIsEnabled()
    {

        return true;
    }

    

    /**
     * @When /^I apply transmogrifier dataset "([^"]*)"$/
     * @When /^I apply dataset "([^"]*)"$/
     */
    public function iApplyTransmogrifierDataset($filename)
    {

        $filename = $this->transmogrifier_dataset_path . $filename;
        if (!file_exists($filename)) {
            throw new \RuntimeException('File not found: ' . $filename);
        }
        if (!$this->db) {
            throw new \RuntimeException('Please connect to a database before applying a dataset');
        }
        
        $ds = new Dataset();
        $ds->loadDatasetFile($filename);
        $ds->applyTo($this->db);
    }

    /**
     * @Then /^I should have "([^"]*)" records in the "([^"]*)" table$/
     */
    public function iShouldHaveRecordsInTheTable($count, $tablename)
    {
        if (!$this->db) {
            throw new \RuntimeException('Please connect to a database before counting records');
        }
        $pdo = $this->db->getPdo();
        $res = $pdo->query('SELECT * FROM ' . $tablename);
        if ($res->rowCount() != $count) {
            throw new \RuntimeException('Rowcount did not match. Expected: ' . $count . '. Rows in database table: ' . $res->rowCount());
        }

    }

     /**
     * @Given /^I connect to database "([^"]*)"$/
     */
    public function iConnectToDatabase($dbname)
    {
        $this->db = new Database();
        $this->db->parseConf($dbname);
        $this->db->connect();
    }
}
