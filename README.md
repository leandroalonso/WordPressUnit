## WordPressUnit

WordPressUnit is a structure to integrate WordPress plugins with the testing framework PHPUnit, providing database connection, seed and cleaner.

## Configuring

First, you need to modify the `wp-config.php` line which defines the database name:

```php
if (defined('TEST_ENVIRONMENT')) define('DB_NAME', 'test_environment');
else define('DB_NAME', 'production_environment');
```

Replace `test_environment` and `production_environment` for the correct values of your environment. This will ensure that our tests is running in the test database (which will be deleted and recreated when we want).

You can add your files and classes to be tested in `bootstrap.php`.

Now, we need to configure `reset.php` file. In the first line of the method `activatePlugin()` you need to specify the path for the main file of your plugin:

```php
public function activatePlugin() {
		activate_plugin('PluginFolderName/main-file.php');
```

Finally, change the URL of your WordPress installation in the first line of `db/data/seed.sql`:

```sql
UPDATE `wp_options` SET `option_value` = 'http://localhost/wordpress' WHERE `option_name` = 'home' OR `option_name` = 'siteurl';
```

This is all! :)

## Database Seeds

You define all your database seed data in the file `seed.sql`, inside the folder `data` in the form of simple SQL queries.

## Calling database from a test

To have access to database in a test, add this to the PHPUnit `setUp()` test method:

```php
function setUp() {
		global $wpdb;
		$this->wpdb = $wpdb;
}
```

Now you can use `$wpdb`. More information about usage and methods [here](https://codex.wordpress.org/Class_Reference/wpdb).

## How to run your tests

To reset your database:

```
php tests/db/reset.php 
```

To run your tests:

```
php vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/.
```

To clean the database AND run your tests:

```
php tests/db/reset.php && php vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/.
```

## Tests running faster

You can uncomment this line in `bootstrap.php`:

```php
#define('SHORTINIT', true);
```

This will make your test runs faster. However, if you make use of WordPress functions (like `update_option` and others) your tests will break. So be carefully.

## Links

The official site for the PHPUnit testing framework is <https://phpunit.de/>.

Documentation for WordPress methods can be found in <https://codex.wordpress.org/>.

Created by Leandro Alonso. If you have any questions feel free to [contact me](http://leandroalonso.com/h3/contato/).