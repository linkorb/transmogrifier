<?php

namespace LinkORB\TransmogrifierExtension;

use Behat\Behat\Context\BehatContext;
use LinkORB\Transmogrifier\Dataset;
use LinkORB\Transmogrifier\Database;

class TransmogrifierContext extends BehatContext
{

    /**
     * Path to dataset files
     * @var string
     */
    private $dataset_dir;

    /**
     * Path to database .conf files
     * @var string
     */
    private $dbconf_dir;

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
    }


    public function setDatasetDir($path)
    {
        $this->dataset_dir = $path;
    }

    public function setDbConfDir($path)
    {
        $this->dbconf_dir = $path;
    }


    /**
     * @Given /^transmogrifier is enabled$/
     */
    public function transmogrifierIsEnabled()
    {
        return true;
    }

    /**
     * @Given /^I connect to database "([^"]*)"$/
     */
    public function iConnectToDatabase($dbname)
    {
        $this->db = new Database();
        $this->db->setDbConfDir($this->dbconf_dir);
        $this->db->parseConf($dbname);
        $this->db->connect();
    }


    /**
     * @When /^I apply transmogrifier dataset "([^"]*)"$/
     * @When /^I apply dataset "([^"]*)"$/
     */
    public function iApplyTransmogrifierDataset($filename)
    {
        $filename = $this->dataset_dir . '/' . $filename;
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
}
