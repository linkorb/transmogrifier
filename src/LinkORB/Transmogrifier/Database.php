<?php

namespace LinkORB\Transmogrifier;

class Database
{
    private $dbname;
    private $host = 'localhost';
    private $username = null;
    private $password = null;
    private $driver = 'mysql';
    private $pdo;

    public function connect()
    {
        if ($this->dbname=='') {
            throw new \InvalidArgumentException('dbname not specified');
        }

        $this->pdo = new \PDO(
            'mysql:host=' . $this->host . ';dbname=' . $this->dbname,
            $this->username,
            $this->password,
            array( \PDO::ATTR_PERSISTENT => false)
        );
    }

    public function parseOptions($input)
    {
        $this->username = $input->getOption('username');
        $this->password = $input->getOption('password');
        $this->host = $input->getOption('host');
        $this->driver = $input->getOption('driver');
        $this->dbname = $input->getOption('dbname');
    }

    public function parseConf($filename)
    {
        if (file_exists('/share/config/database/' . $filename . '.conf')) {
            $filename = '/share/config/database/' . $filename . '.conf';
        }
        if (file_exists($filename)) {
            $ini = parse_ini_file($filename, false, INI_SCANNER_RAW);
            $this->dbname = $ini['name'];
            $this->username = $ini['username'];
            $this->password = $ini['password'];
            $this->host = $ini['server'];



        } else {
            throw new \RuntimeException("dbconf file not found: " . $filename);
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function getName()
    {
        return $this->dbname;
    }
}
