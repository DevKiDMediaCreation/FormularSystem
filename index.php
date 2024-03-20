<?php
//error_reporting(0);
include 'config/database.php';
include 'config/fbs.php';
include 'utils/anonymtoken.php';

$token = created();

// Get all forms
$data = request("SELECT * FROM form WHERE visibility = 'public'");

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo requestSystem("SELECT * FROM information WHERE name = 'title'")->fetch(PDO::FETCH_ASSOC)['value']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/main.css">
</head>
<body>

<div class="header bg-black py-2">
    <div class="container" style="color: white;">
        <h1 class="me-2">Home</h1>
        <small class="text-secondary me-1 break">Organisation: <?php echo getOrganization(); ?></small>
        <p class="text-secondary me-2 break">Token: <?php echo $token; ?></p>
    </div>
</div>

<div class="album py-5 bg-body-tertiary">
    <div class="container">
        <?php if ($data->rowCount() == 0) {
            echo "<h2>Keine sichtbaren Formulare gefunden</h2>";
        } ?>


        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php

            for ($i = 0; $row = $data->fetch(PDO::FETCH_ASSOC); $i++) {
                ?>

                <div class="col p-1">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h1 class="card-title"><?php echo $row['title']; ?></h1>
                            <p class="card-subtitle mb-2 text-body-secondary"><?php echo $row['description']; ?></p>
                            <p>Thema: <?php echo $row['subject']; ?></p>
                            <small class="text-secondary">
                                <?php
                                $g = null;
                                if (!empty($row['meta'])) {
                                    $g = explode(";", $row['meta'])[0];
                                }
                                ?>
                                <?php echo "Klasse: " . $g ?? "Keiner Klasse"; ?>
                            </small>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="form.php?id=<?php echo $row['formularid']; ?>&token=<?php echo $token; ?>"
                                   class="btn btn-outline-black">Zum
                                    Formular</a><br>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>

