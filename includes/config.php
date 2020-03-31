<?php
include_once('config.inc.php');

define('DEBUG', TRUE);
define('ROLLBAR_TOKEN', '75e4a54f8f5e4866980683ce7ec68c3d');

if (isset($_SERVER['APPLICATION_ENV']) && $_SERVER['APPLICATION_ENV'] == 'development') {
    define('SOURCE_PATH', realpath(dirname(__DIR__)) . '/');
    $db_arr = [
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'username' => 'root',
        'passwd'   => 'root',
        'dbname'   => 'db518818765',
        'socket'   => ''
    ];
} else {
    define('SOURCE_PATH', '/share/MD0_DATA/Web/virtualcampaign/migration/');
}
