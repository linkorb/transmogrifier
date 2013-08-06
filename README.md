# Transmogrifier

Transmogrifier is a tool to help you setup your database contents before testing, through fixtures.

## Examples

The `examples` directory contains a few datasets you can use to try out Transmogrifier,
see how it works, and copy as a starting-point for your own datasets.

### Create the schema

The examples will ensure 2 users, 'Calvin' and 'Hobbes', are registed in your `user` table.

Before you can try these out, use the following SQL to generate the `user` table in your `test` database:

    CREATE TABLE user (id int, name varchar(16), email varchar(32), password varchar(32));

