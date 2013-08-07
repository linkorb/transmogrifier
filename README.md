# Transmogrifier
<img src="http://www.linkorb.com/d/online/linkorb/upload/transmogrifier.gif" align="right" />

Transmogrifier is a tool to help setup your database fixtures before running your tests.

You can use it in the following ways:

* As a simple command-line utility (great for your build scripts and continuous integration)
* As a PHP library
* As a set of Gherkin/Behat statements for automated BDD tests

## Command-line utility

You can use Transmogrifier as a command-line utility (stand-alone, or as part of your PHP project through composer).

### Standalone

0. clone this git repository
0. run `composer install` to install all dependencies
0. run `bin/transmogrifier --help` for a list of available commands

### Through composer, in your PHP project:

Open your `composer.json` file, and add this to the `require` section:

    "linkorb/transmogrifier": "dev-master"

You can now run `vendor/bin/transmogrifier`.

### Adding transmogrifier commands to an existing Symfony/Console application

Add the following line to an existing Symfony/Console application in order to enable the Transmogrifier commands to it:

    $application->add(new \LinkORB\Transmogrifier\Command\DatasetApplyCommand());

### Available commands

#### transmogrifier:datasetapply

The most interesting usage through the command-line is the `transmogrifier:applydataset` command.

You can use it like this:

    bin/transmogrifier transmogrifier:applydataset --dbname=test example/user.yml
    
This command will ensure that the `dbname` database contains the dataset specified in `example/user.yml`

## PHP Library

You can use the Transmogrifier very easily from within your own PHP projects as a library.

### Installing the library through composer

pen your `composer.json` file, and add this to the `require` section:

    "linkorb/transmogrifier": "dev-master"

### How to use the library

The 2 main classes are:

    * `Dataset`: A class that can load datasets from files, and apply them to databases.
    * `Database`: A connection to a database, providing helpers for initializing the connection. 

Here's an example usage:

    $db = new Database();
    // Optionally initialize db parameters by file, cli options, or explicit values
    $db->parseConf('/path/to/my/dbconf/test.conf');
    $db->connect();

    $dataset = new Dataset();
    $dataset->loadDatasetFile('/path/to/my/dataset.yml');
    $dataset->applyTo($db);

## Behat Extension

You can use Transmogrifier directly from your Behat `.feature` files!

Adding the extension will activate a few new Gherkin commands to help you initialize your database testing fixtures.

### Installing the extension through composer

Open your `composer.json` file, and add this to the `require` section:

    "linkorb/transmogrifier": "dev-master"

### Enabling the Behat extension

Edit your `features/bootstrap/FeatureContext.php` file, and add the following line to the `__construct` method:

    $this->useContext(
        'transmogrifier',
        new \LinkORB\Transmogrifier\BehatExtension\TransmogrifierContext($parameters)
    );

### How to use the extension

You can use the following new syntax in your `.feature` files:

    Scenario: Applying a yml dataset to the `test` database
        Given I connect to database "test"
        When I apply dataset "user.yml"
        Then I should have "2" records in the "user" table

This example scenario will connect to the database `test`, load dataset `user.yml`, and apply it.
After that it will verify the `user` table contains 2 records (just like the yml file).

### Configuring Behat

For this to work, you will need to tell Behat and Transmogrifier where to find your datasets, and where to find your database config files.

Edit your `behat.yml` file, and add the following:

    default:
        context:
            parameters:
                transmogrifier_dbconf_path: /path/to/my/dbconf-files/
                transmogrifier_dataset_path: /path/to/my/dataset-files/

These paths can be either absolute or relative from the directory where you start Behat.

### Behat example

The `features/` directory in this repository contains a fully functional `transmogrifier.feature` file.

## Supported file-formats

The dataset importer is based on phpunit/dbunit. It currently supports the following file-formats:

- YAML
- Flat XML
- XML
- CSV

The Dataset loader guesses the format based on the file-extension.

Please refer to the `example/` directory for datasets in these formats.
The PHPUnit documentation contains further information about the loaders: [link](http://phpunit.de/manual/current/en/database.html)

## Database .conf files

To simplify connecting to your database, Transmogrify can load conneciton settings from a simple `.conf` file.

An example file looks like this:

    name=test
    server=127.0.0.1
    username=susie
    password=mrbun
    driver=mysql

You can use these `.conf` files in all Transmogrifier modes: Command-line, Behat, or library.
The connection is established through `PDO`, so all PDO supported databases will work.

## Example datasets

The `examples/` directory contains a few datasets you can use to try out Transmogrifier,
see how it works, and copy as a starting-point for your own datasets.

### Create the schema

The examples will ensure 2 users, 'Calvin' and 'Hobbes', are registed in your `user` table.

Before you can try these out, use the following SQL to generate the `user` table in your `test` database:

    CREATE TABLE user (id int, name varchar(16), email varchar(32), password varchar(32));

