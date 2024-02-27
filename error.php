<?php
if (isset($_GET['err'])) {
    $err = $_GET['err'];

    $meta = $err;

    if(isset($_GET['meta'])) {
        $meta = $_GET['meta'];
    }

    if ($err == "dberrorselect") {
    } elseif ($err == "noncredentails") {
    } elseif ($err == "nonexisterror") {
    } elseif ($err == "nonparam") {
    }
    else {
        header("Location: error.php?err=nonexisterror&meta={$err}");
    }
} else {
    header("Location: error.php?err=noncredentails");
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="header bg-black py-2">
    <div class="container" style="color: white;">
        <h1 class="me-2"><?php echo $err; ?></h1>
    </div>
</div>
<div class="container bg-danger rounded border my-2">
    <p><?php echo $meta; ?></p>
</div>
</body>
