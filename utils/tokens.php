<?php
global $conn;
include("../config/dbmysqli.php");

session_start();


function Tokens()
{
    global $conn;

    if (isset($_SESSION['user']) && isset($_SESSION['pw'])) {
        // Check first if the user exist and get the id
        $sql = "SELECT * FROM users WHERE user = '" . $_SESSION['user'] . "' AND pw = '" . $_SESSION['pw'] . "'";
        $result = $conn->query($sql);

        // Check if only one row is returned
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
        } else {
            echo "User does not exist";
            return null;
        }

        // Request to tokens table
        $sql = "SELECT * FROM tokens WHERE userid = '" . $user_id . "'";
        $result = $conn->query($sql);

        // return all tokens

        $tokens = array();

        while ($row = $result->fetch_assoc()) {
            array_push($tokens, $row['token']);
        }
        return $tokens;

    } else {
        echo "You are not logged in";
        return null;
    }
    return null;
}

$tokens = Tokens();

if ($tokens) {
    foreach ($tokens as $element) {
        echo $element . "<br>";
    }
}

function generateTokens($id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE id = '" . $_GET['id'] . "'";
    $result = $conn->query($sql);

    $time = time();

    $s = "";
    for ($i = 0; $i < 10; $i++) {
        $s .= chr(rand(65, 90));
    }

    // Join all the characters of result to s
    $s = implode("", $result->fetch_assoc()) . $s . $time;

    $s = base64_encode($s);
    $s = hash("sha256", $s);
    return $s;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    echo generateTokens($_GET['id']);
}