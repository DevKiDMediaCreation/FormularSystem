<?php
define('DBFBS_US', 'admin');
define('DBFBS_PW', 'admin');
define('DBFBS_HOST', 'localhost');
define('DBFBSN', 'fbs');

try {
    $fbsdpo = new PDO("mysql:host=" . DBFBS_HOST . ";dbname=" . DBFBSN, DBFBS_US, DBFBS_PW);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

