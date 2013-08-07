# Transmogrifier
<img src="http://www.linkorb.com/d/online/linkorb/upload/transmogrifier.gif" align="right" />

Transmogrifier is a tool to help setup your database fixtures before running your tests.

You can use it in the following ways:

1. As a simple command-line utility (great for your build scripts and continuous integration)
2. As a PHP library
3. As a set a Behat extension, enabling Gherkin statements for automated BDD database tests

## 1. Command-line utility

You can use Transmogrifier as a command-line utility (stand-alone, or as part of your PHP project through composer).

### Standalone

0. clone this git repository
0. run `composer install` to install all dependencies
0. run `bin/transmogrifier --help` for a list of available commands

### Through composer, in your PHP project:

Open your `composer.json` file, and add this to the `require` section:

```json
"linkorb/transmogrifier": "dev-master"
```

You can now run `vendor/bin/transmogrifier`.

### Adding transmogrifier commands to an existing Symfony/Console application

Add the following line to an existing Symfony/Console application in order to enable the Transmogrifier commands to it:

```php
$application->add(new \LinkORB\Transmogrifier\Command\DatasetApplyCommand());
```

### Available commands

#### transmogrifier:datasetapply

The most interesting usage through the command-line is the `transmogrifier:applydataset` command.

You can use it like this:

```
bin/transmogrifier transmogrifier:applydataset --dbname=test example/user.yml
```

This command will ensure that the `dbname` database contains the dataset specified in `example/user.yml`

## 2. PHP Library

You can use the Transmogrifier very easily from within your own PHP projects as a library.

### Installing the library through composer

pen your `composer.json` file, and add this to the `require` section:

```json
"linkorb/transmogrifier": "dev-master"
```

### How to use the library

The 2 main classes are:

* `Dataset`: A class that can load datasets from files, and apply them to databases.
* `Database`: A connection to a database, providing helpers for initializing the connection. 

Here's an example usage:

```php
$db = new Database();
// Optionally initialize db parameters by file, cli options, or explicit values
$db->parseConf('/path/to/my/dbconf/test.conf');
$db->connect();

$dataset = new Dataset();
$dataset->loadDatasetFile('/path/to/my/dataset.yml');
$dataset->applyTo($db);
```

## 3. Behat Extension

There is a Transmogrifier Extension available for Behat!

This allows you to use Transmogrifier directly from your Behat .feature files.

Check out the extension and it's documentation here:

* [https://github.com/linkorb/transmogrifierextension](https://github.com/linkorb/transmogrifierextension)

## Supported file-formats

The dataset importer is based on phpunit/dbunit. It currently supports the following file-formats:

- YAML
- Flat XML
- XML
- CSV

The Dataset loader guesses the format based on the file-extension.

Please refer to the `example/` directory for datasets in these formats.
The PHPUnit documentation contains further information about the loaders: 

* [PHPUnit DBUnit documentation](http://phpunit.de/manual/current/en/database.html)

## Database .conf files

To simplify connecting to your database, Transmogrify can load conneciton settings from a simple `.conf` file.

An example file looks like this:

```ini
name=test
server=127.0.0.1
username=susie
password=mrbun
driver=mysql
```

You can use these `.conf` files in all Transmogrifier modes: Command-line, Behat, or library.
The connection is established through `PDO`, so all PDO supported databases will work.

## Example datasets

The `examples/` directory contains a few datasets you can use to try out Transmogrifier,
see how it works, and copy as a starting-point for your own datasets.

### Create the schema

The examples will ensure 2 users, 'Calvin' and 'Hobbes', are registed in your `user` table.

Before you can try these out, use the following SQL to generate the `user` table in your `test` database:

```sql
CREATE TABLE user (id int, name varchar(16), email varchar(32), password varchar(32));
```
