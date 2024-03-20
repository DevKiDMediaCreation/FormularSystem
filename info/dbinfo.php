<?php
$host = 'localhost';
$db   = 'fbs';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

$attributes = array(
    "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
    "ORACLE_NULLS", "PERSISTENT", "SERVER_INFO", "SERVER_VERSION",
);
foreach ($attributes as $val) {
    echo "<p>PDO::ATTR_$val: ";
    echo $pdo->getAttribute(constant("PDO::ATTR_$val")) . "</p>";
}

echo 'PDO driver name: ' . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "<br>";
echo 'Database server version: ' . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "<br>";
echo 'Client version: ' . $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION) . "<br>";

// Get the list of tables
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_NUM);

foreach ($tables as $table) {
    echo "<h2>Table: " . $table[0] . "</h2>";

    // Get the structure of the table
    $stmt = $pdo->query("DESCRIBE " . $table[0]);
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($structure);
    echo "</pre>";
}
?>