# Transmogrifier
<img src="http://www.linkorb.com/d/online/linkorb/upload/transmogrifier.gif" align="right" />

Transmogrifier is a tool to help setup your database fixtures before running your tests.

You can use it in the following ways:

* As a simple command-line utility (great for your build scripts and continuous integration)
* As a PHP library
* As a set of Gherkin/Behat statements for automated BDD tests

## Command-line utility

You can use Transmogrifier as a command-line utility by following these steps:

0. clone this git repository
0. run `composer install` to install all dependencies
0. run `bin/console --help` for a list of available commands

### transmogrifier:datasetapply

The most interesting usage through the command-line is the `transmogrifier:applydataset` command.

You can use it like this:

    bin/console transmogrifier:applydataset --dbname=test example/user.yml
    
This command will ensure that the `dbname` database contains the dataset specified in `example/user.yml`

## Supported file-formats

The dataset importer is based on phpunit/dbunit. It supports the following file-formats:

- YAML
- Flat XML
- XML
- CSV

Please refer to the PHPUnit documentation for further information: [link](phpunit.de/manual/current/en/database.html)

## Example datasets

The `examples/` directory contains a few datasets you can use to try out Transmogrifier,
see how it works, and copy as a starting-point for your own datasets.

### Create the schema

The examples will ensure 2 users, 'Calvin' and 'Hobbes', are registed in your `user` table.

Before you can try these out, use the following SQL to generate the `user` table in your `test` database:

    CREATE TABLE user (id int, name varchar(16), email varchar(32), password varchar(32));

