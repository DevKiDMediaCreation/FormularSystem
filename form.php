<?php
include('config/database.php');

if (!empty($_GET['id']) && !empty($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];

    $dbs = ["formular", "form", "tokens"];
    $column = ["formularid", "formularid", "token"];

    $parameter = [$id, $id, $token]; // Corrected parameter values

    for ($i = 0; $i < count($dbs); $i++) {
        $stmt = request("SELECT * FROM " . $dbs[$i] . " WHERE `" . $column[$i] . "` = :id", [':id' => $parameter[$i]]);
        $row[] = ($i == 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row[$i]) {
            $temp = json_encode($row[$i]);
            header("Location: error.php?err=dberrorselect&meta=Table: {$dbs[$i]} ID: {$_GET['id']} Table: {$dbs[$i]} Token: {$_GET['token']} non_checked");
            exit;
        }
    }

    $disable = false;
    if ($row[2]['expired'] < date("Y-m-d H:i:s")) {
        $status = "Token ist abgelaufen seit: " . $row[2]['expired'] . ". Für einen Token bitte hier drücken.
        <a href='./utils/anonymtoken.php?formular={$id}' class='btn btn-warning border-black border my-2 w-100'>Erstellen</a>
        ";
        $disable = true;
    }/* else if ($row[2]['used'] == 1) {
        $status = "Token wurde bereits verwendet. Für einen Token bitte hier drücken. <a href='./utils/anonymtoken.php?formular={$id}' class='btn btn-warning border-black border my-2 w-100'>Erstellen</a>";
        $disable = true;
    }*/

    $i = count($dbs);
    $row[$i] = request("SELECT * FROM users WHERE `id` = :id", [':id' => $row[1]['creatorid']])->fetch(PDO::FETCH_ASSOC);
    if (!$row[$i]) {
        $temp = json_encode($row[$i]);
        header("Location: error.php?err=dberrorselect&meta=Table: users ID: {$_GET['id']} Table: users Token: {$_GET['token']} non_checked");
        exit;
    }

    // Sort by index
    usort($row[0], function ($a, $b) {
        return $a['index'] <=> $b['index'];
    });
} else {
    header("Location: index.php");
    #header("Location: error.php?err=nonparam&meta=providing_requirements: id, token");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/main.css">
</head>
<body class="raster">

<div class="header bg-black py-2">
    <div class="container" style="color: white;">
        <div style="display: flex; flex-direction: row; align-items: baseline;">
            <h1 class="me-2"><?php echo $row[1]['title']; ?></h1>
            <small class="text-secondary">Bei/Von <?php echo $row[3]['name']; ?></small>
        </div>
        <small class="text-secondary me-1 break"><?php echo $row[1]['description']; ?></small>
        <?php if (!empty($row[1]['subject'])) { ?>
            <small class="text-secondary me-1 break">Thema: <?php echo $row[1]['subject']; ?></small>
        <?php } ?>
        <div style="display: flex; flex-direction: row; align-items: baseline;" class="py-2">
            <p class="text-secondary me-2 break">Token: <?php echo $row[2]['token']; ?></p>
            <p class="text-secondary me-2 break">ID: <?php echo $id; ?></p>
        </div>

    </div>
</div>
<div class="container">
    <?php if (!empty($status)) { ?>
        <div id="status" class="bg-danger text-center border rounded p-2 my-2 text-white">
            <?php echo $status; ?>
        </div>
    <?php } ?>
    <form class="needs-validation" novalidate="" method="post">
        <?php
        foreach ($row[0] as $item) {
            $required = $item['required'] == 1 ? "required" : "";
            ?>
            <div class="rounded border my-2 p-2 bg-white">
                <div class="" style="display:flex; align-content: baseline">
                    <h3 class='py-2'><?php echo $item['question'] ?></h3>

                    <?php if ($required == "required") { ?>
                        <h4 class="text-danger">*</h4>
                    <?php } ?>
                </div>
                <?php
                if ($item['description']) { ?>
                    <p class='text-secondary'>Beschreibung: <?php echo $item['description'] ?></p>
                <?php } ?>


                <?php
                if ($item['answertype'] == "text") {
                    include('template/text.php');
                } elseif ($item['answertype'] == "star") {
                    include('template/star.php');
                } elseif ($item['answertype'] == "read") {
                    include('template/read.php');
                } elseif ($item['answertype'] == "select") {
                    include('template/select.php');
                } elseif ($item['answertype'] == "choice") {
                    include('template/choice.php');
                } ?>
            </div>
            <?php
        } ?>
        <button class="btn btn-primary my-2 w-100 <?php if ($disable) {
            echo "disabled";
        } ?>">Absenden
        </button>
    </form>
</div>


<?php

?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="assets/require.js"></script>
</body>
</html>