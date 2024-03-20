<?php
function status($host, $port, $timeout = "0.5") {
    $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (is_resource($connection)) {
        fclose($connection);
        echo '<span>Server is up and running.</span>';

        return true;
    } else {
        echo '<span>Unable to connect to server.</span>';

        return false;
    }

}
