<?php
define('DB_DSN', 'mysql:dbhost=localhost;dbname=scrum');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('ROOT_DIR', realpath(dirname(__FILE__) . '/..'));

function __autoload($class) {
	require ROOT_DIR . '/lib/' . $class . '.php';
}

require ROOT_DIR . '/lib/helper/ViewHelper.php';
