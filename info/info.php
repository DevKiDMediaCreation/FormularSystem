<?php
include("../config/fbs.php");
include("server.php");

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>INFO - NON-Adminstration Rights</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">

    <link href="../assets/font.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<div class="header bg-black py-2">
    <div class="container" style="color: white;">
        <h1 class="me-2">Info/Version</h1>
        <small class="text-secondary me-1 break">Organisation: <?php echo getOrganization(); ?></small>
        <p class="text-secondary me-2 break">Version: <?php echo getVersion(); ?></p>
    </div>
</div>

<div class="py-2">
    <div class="container text-black">
        <p class="me-2 break">Dev: Duy Nam Schlitz</p>
        <iframe src="phpInfo.php" width="100%" height="800px"></iframe>
        <h2>Database Information</h2>
        <?php include("dbinfo.php") ?>

        <h2>Status:</h2>
        Database: <?php status($host = "localhost", $port = 3306); ?>
        AuthServer: <?php status($host = "localhost", $port = 12536); ?>
        Okta: <?php status($host = "localhost", $port = 2635); ?>
        Update Listener: <?php status($host = "localhost", $port = 36426); ?>
        Cosmolang Integration: <?php status($host = "localhost", $port = 4387); ?>
        Interface IPL: <?php status($host = "localhost", $port = 1306); ?>
        Archive: <?php status($host = "localhost", $port = 3506); ?>
        Web: <?php status($host = "localhost", $port = 80); ?>
        HTTPS: <?php status($host = "localhost", $port = 443); ?>

        <h2>System Information</h2>

        <p>System: <?php echo php_uname(); ?></p>
        <p>PHP Version: <?php echo phpversion(); ?></p>
        <p>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? null; ?></p>
        <p>Server Name: <?php echo $_SERVER['SERVER_NAME']; ?></p>
        <p>Server Address: <?php echo $_SERVER['SERVER_ADDR']; ?></p>
        <p>Server Port: <?php echo $_SERVER['SERVER_PORT']; ?></p>
        <p>Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
        <p>Server Admin: <?php echo $_SERVER['SERVER_ADMIN'] ?? null; ?></p>
        <p>Server Signature: <?php echo $_SERVER['SERVER_SIGNATURE'] ?? null; ?></p>
        <p>Server Protocol: <?php echo $_SERVER['SERVER_PROTOCOL']; ?></p>
        <p>Gateway Interface: <?php echo $_SERVER['GATEWAY_INTERFACE']; ?></p>
        <p>Request Method: <?php echo $_SERVER['REQUEST_METHOD']; ?></p>

        <h2>PHP Information</h2>
        <p>PHP Version: <?php echo phpversion(); ?></p>

    </div>
</div>

<?php include "../template/footer.php"; ?>
</body>
</html>