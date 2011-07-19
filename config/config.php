<?php
define('DB_DSN', 'mysql:dbhost=localhost;dbname=scrum');
define('DB_USERNAME', 'user');
define('DB_PASSWORD', 'pass');
define('ROOT_DIR', realpath(dirname(__FILE__) . '/..'));

function __autoload($class) {
	require ROOT_DIR . '/lib/' . $class . '.php';
}

require ROOT_DIR . '/lib/helper/ViewHelper.php';
