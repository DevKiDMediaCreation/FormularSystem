<?php
define('DB_USER', 'root'); // Replace 'your_username' with your actual database username
define('DB_PASSWORD', 'root'); // Replace 'your_password' with your actual database password
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedbacksys'); // Replace 'feedbacksys' with your actual database name

try {
    $dbpdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
   $sth = $dbpdo->query('SELECT * FROM users');
   // Done; close it
    $sth = null;
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

