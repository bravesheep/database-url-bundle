# BravesheepDatabaseUrlBundle
A Symfony2 bundle for parsing the contents of a url that specifies which database to use.

## Installation and configuration
Using [Composer][composer] add the bundle to your dependencies using the require command:
`composer require bravesheep/database-url-bundle:dev-master`.

### Add the bundle to your AppKernel
Add the bundle in your `app/AppKernel.php`. **Note**: in order for the parameters defined by this bundle to be picked
up by Doctrine, you need to include this bundle before including the `Doctrine\Bundle\DoctrineBundle\DoctrineBundle` 
bundle.

```php
public function registerBundles()
{
    return array(
        // ...
        new Bravesheep\DatabaseUrlBundle\BravesheepDatabaseUrlBundle(),
        // ...
    );
}
```

### Configure which urls should be rewritten to parameters
For this bundle to work you need to specify which urls need to be rewritten to basic parameters. This bundle can handle
any number of urls by configuring the correct properties under `bravesheep_database_url.urls`. Take a look at this
example configuration:

```yaml
bravesheep_database_url:
    urls:
        default:
            url: %database_url%
            prefix: database_
```

In this case we take the value of the `database_url` parameter and create parameters from it prefixed with `database_`.

## Usage
Take a look at this `parameters.yml.dist` which is distributed by the Symfony2 Standard Edition:

```yaml
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: ~
    database_name: symfony
    database_user: root
    database_password: ~

    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: ~
    mailer_password: ~

    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt

    debug_toolbar: true
    debug_redirects: false
    use_assetic_controller: true
```

As you can see there is a grand total of 6 parameters required for just specifying the database connection. If we could
instead use a URL for specifying the database this might be reduced to just this:

```yaml
parameters:
    database_url: mysql://root@127.0.0.1/symfony
    
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: ~
    mailer_password: ~

    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt

    debug_toolbar: true
    debug_redirects: false
    use_assetic_controller: true
```

This is exactly what the BravesheepDatabaseUrlBundle is supposed to do. The example configuration in the previous
section reads this `database_url` parameter and creates the individual `database_driver`, `database_host`, 
`database_port`, `database_name`, `database_user` and `database_password`.

In general this bundle takes any database url and creates the following parameters, prefixed with the prefix you
specify: `driver`, `host`, `port`, `name`, `user`, `password`, `path` and `memory`. The `path` and `memory` variables
are used to indicate the SQLite path and a boolean indicating whether to use an in-memory SQLite database respectively.

### Accepted urls
URLs are generally formatted in `scheme://user:password@host:port/database` format. The following schemes are
understood:

* `postgres`, `postgresql`, `pgsql` and `pdo_pgsql` for the `pdo_pgsql` (PostgreSQL) driver
* `mysql` and `pdo_mysql` for the `pdo_mysql` (MySQL) driver
* `sqlite` and `pdo_sqlite` for the `pdo_sqlite` (SQLite) driver
* `mssql` and `pdo_mssql` for the `pdo_mssql` (MSSQL) driver

Username and password can be omitted if they are not required, as well as the port in case it is the default. For SQLite
the format to use is:

* `sqlite:///path/to/sqlite/db` for an absolute path to a SQLite database.
* `sqlite:///%kernel.root_dir%/to/db.sql?relative` for a relative path using the kernel.root_dir as the base
* `sqlite://:memory:` for an in-memory database

[composer]: https://getcomposer.org/
