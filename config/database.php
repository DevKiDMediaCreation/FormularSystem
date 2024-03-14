<?php
require_once __DIR__ . "/fbs.php";

$DB_USER = 'root';
$DB_PASSWORD = 'root';
$DB_HOST = 'localhost';
$DB_NAME = getDatabaseInformation();

try {
    $dbpdo = new PDO("mysql:host=".$DB_HOST.";dbname=".$DB_NAME["value"], $DB_USER, $DB_PASSWORD);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

if (!function_exists('request')) {
    function request($sql, $params = [])
    {
        global $dbpdo;
        $stmt = $dbpdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}