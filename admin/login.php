<?php
include("../config/database.php");
session_start();
global $dbpdo;

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo "Please fill in all fields";
} else {
    $user = $_POST['username'];
    $pw = $_POST['password'];

    // Escape user input to prevent SQL injection
    //$user = $dbpdo->quote($user);
    $user = base64_encode($user);

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE user = :user";
    $stmt = $dbpdo->prepare($sql);
    $stmt->bindParam(':user', $user);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row == null) {
        echo "User does not exist";
        session_destroy();
        die();
    }

    // Verify password hash
    $pw = hash('sha256', base64_encode($pw));

    if ($pw !== $row['pw']) {
        echo "Incorrect password";
        session_destroy();
        die();
    }

    // Store user session data
    $_SESSION['user'] = $row['user'];
    $_SESSION['pw'] = $row['pw'];
    $_SESSION['id'] = $row['id'];
    $_SESSION['rights'] = $row['rights'];

    echo "Login successful";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Form</title>
</head>
<body>
<div style="container">
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
