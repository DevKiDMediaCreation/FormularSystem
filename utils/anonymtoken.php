<?php
global $dbpdo;
include '../config/database.php';
include 'randomString.php';
include 'tokens.php';


function created()
{
    global $dbpdo;
    $token = generateAnonymousToken(
        RandomString(rand(4, 40)), rand(3, 299));
    $expired = date("Y-m-d H:i:s", strtotime("+1 days"));

    $sql = "INSERT INTO `tokens` (`token`, `userid`, `expired`) VALUES ('{$token}', 2, '{$expired}');"; // Use 'users' table directly
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute();

    return $token;
}

if (!empty($_GET['formular'])) {
    header("Location: ../form.php?id={$_GET['formular']}&token=" . created());
}