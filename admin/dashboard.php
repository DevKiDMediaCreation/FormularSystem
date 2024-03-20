<?php
include("../config/database.php");
include("../config/fbs.php");
session_start();

if(!$_SESSION) {
    header("Location: ./login.php");
}

?>


