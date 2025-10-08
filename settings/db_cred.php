<?php
//Database credentials
// Settings/db_cred.php

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'dbforlab');


if (!defined("DB_SERVER")) {
    define("DB_SERVER", "localhost");
}

if (!defined("DB_USERNAME")) {
    define("DB_USERNAME", "root");
}

if (!defined("DB_PASSWORD")) {
    define("DB_PASSWORD", "");
}

if (!defined("DB_NAME")) {
<<<<<<< HEAD
=======
    // Use the database name from the provided SQL dump
>>>>>>> ccf60bc0925d5da4c196d7ac0a7fd89ac8dc8f46
    define("DB_NAME", "dbforlab");
}
?>