<?php

namespace LinkORB\Transmogrifier\Command;

use LinkORB\Transmogrifier\Dataset;
use LinkORB\Transmogrifier\Database;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatasetApplyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('transmogrifier:applydataset')
            ->setDescription('Apply transmogrifier dataset to a database')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Transmogrifier dataset filename'
            )
            ->addOption(
                'dbname',
                null,
                InputOption::VALUE_REQUIRED,
                'Database name'
            )
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                'Specify sourcefile format. Available options: flatxml, json or yaml'
            )
            ->addOption(
                'tablename',
                null,
                InputOption::VALUE_REQUIRED,
                'In case of a .csv file, specify the tablename that the dataset applies to'
            )
            ->addOption(
                'username',
                null,
                InputOption::VALUE_REQUIRED,
                'The username to use when connecting to the database server'
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_REQUIRED,
                'The password to use when connecting to the database server'
            )
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                'The host of the database server to connect to'
            )
            ->addOption(
                'driver',
                null,
                InputOption::VALUE_REQUIRED,
                'The PDO driver to use'
            )
            ->addOption(
                'dbconf',
                null,
                InputOption::VALUE_REQUIRED,
                'Database configuration file'
            )
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $filename = $input->getArgument('filename');

        $db = new Database();
        $db->parseOptions($input);
        $dbconf = $input->getOption('dbconf');
        if ($dbconf!='') {
            $db->parseConf($dbconf);
        }

        $db->connect();

        $output->writeln('Importing transmogrifier file "' . $filename .'" into database "' . $db->getName() . '"');

        $f = new Dataset();

        $tablename = $input->getOption('tablename');
        if ($tablename!='') {
            $f->setTablename($tablename);
        }
        $f->loadDatasetFile($filename, $format);
        $f->applyTo($db);

    }
}
