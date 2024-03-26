<?php
include("../config/database.php");
include("../config/fbs.php");
session_start();

if (!$_SESSION) {
    header("Location: ./login.php");
}

$form = request("SELECT * FROM form WHERE creatorid = :id", [":id" => $_SESSION["id"]]);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Dashboard </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">

    <link href="../assets/font.css" rel="stylesheet">
    <link href="../assets/main.css" rel="stylesheet">
</head>
<body>
<a href="create.php" class="border btn btn-black m-3 px-lg-5 py-lg-4">Formular erstellen</a>
<a href="createtemplate.php" class="border btn btn-black m-3 px-lg-5 py-lg-4">Vorlage erstellen</a>

<div class="album py-5 bg-body-tertiary">
    <div class="container">
        <?php if ($form->rowCount() == 0) {
            echo "<h2>Keine Formulare gefunden</h2>";
        } ?>


        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php

            for ($i = 0; $row = $form->fetch(PDO::FETCH_ASSOC); $i++) {
                ?>

                <div class="col p-1">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo $row['title']; ?></h2>
                            <small class="text-secondary">
                                <?php echo $row['type'] == "form" ? "Formular" : "Vorlage"; ?>
                            </small>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="edit.php?id=<?php echo $row['formularid']; ?>"
                                   class="btn btn-outline-black"><?php echo $row['type'] == "form" ? "Formular" : "Vorlage"; ?>
                                   bearbeiten</a><br>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>