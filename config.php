<?php
define('MODE','dev'); // dev ou prod

if(MODE === 'dev') {
    ini_set('display_errors',1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors',0);
}

//connection
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_HOST', 'localhost');
define('DB_NAME', 'skills_23');

try {
    $connect = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}
catch(Exception $e) {
    die('Erreur : ' . $e -> getMessage());
}

session_start();

include ('headers.php');
include_once('functions.php');
