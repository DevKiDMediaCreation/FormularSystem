<?php
$dbfbs_us = 'admin';
$dbfbs_pw = 'admin';
$dbfbs_host = 'localhost';
$dbfbsn = 'fbs';

try {
    $fbsdpo = new PDO("mysql:host=" . $dbfbs_host . ";dbname=" . $dbfbsn, $dbfbs_us, $dbfbs_pw);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

if (!function_exists('getDatabaseInformation')) {
    function getDatabaseInformation()
    {
        global $fbsdpo;
        $sql = "SELECT * FROM information WHERE name = 'database_name'";
        $stmt = $fbsdpo->prepare($sql);
        $stmt->execute();

        return $stmt->fetch();
    }
}

if (!function_exists('requestSystem')) {
    function requestSystem($sql, $params = [])
    {
        global $fbsdpo;
        $stmt = $fbsdpo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}