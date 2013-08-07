<?php

namespace LinkORB\Transmogrifier;

use LinkORB\Transmogrifier\Database;

class Dataset
{
    private $dataset;
    private $tablename; // for csv files

    public function __construct($filename = null)
    {
        if ($filename) {
            $this->loadDatasetFile($filename);
        }
    }

    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
    }

    public function loadDatasetFile($filename, $format = '')
    {
        if ($format == '') {
            // try to deduce format from filename/extension
            $info = pathinfo($filename);
            $format = $info['extension'];
            if (substr($filename, -9, 9)=='.flat.xml') {
                $format = 'flatxml';
            }
        }

        if ($format == '') {
            // absolute default
            $format = 'yaml';
        }

        switch($format) {
            case "flatxml":
                $ds = new \PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet($filename);
                break;
            case "csv":
                if ($this->tablename=='') {
                    throw new \InvalidArgumentException('You need to specify a tablename for .csv datasets');
                }
                $ds = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(';');
                $ds->addTable($this->tablename, $filename);
                break;
            case "yml":
            case "yaml":
                $ds = new \PHPUnit_Extensions_Database_DataSet_YamlDataSet($filename);
                break;
            case "xml":
                $ds = new \PHPUnit_Extensions_Database_DataSet_XmlDataSet($filename);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported format: ' . $format);
                break;
        }

        $this->dataset = $ds;
    }

    public function applyTo(Database $db)
    {
        $pdo = $db->getPdo();
        $connection = new \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection($pdo, $db->getName());
        $databasetester = new \PHPUnit_Extensions_Database_DefaultTester($connection);
        $setupoperation = \PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT();
        $databasetester->setSetUpOperation($setupoperation);
        $databasetester->setDataSet($this->dataset);
        $databasetester->onSetUp();
    }
}
