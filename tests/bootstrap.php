<?php
/**
 * Bootstrap for PHPUnit
 * php /htdocs/phpunit.phar --bootstrap=bootstrap.php tests/*Test.php
 */

system('clear');

require_once('autoloader.php');
spl_autoload_register('Autoloader::loader');
