<?php
global $dbpdo;
include('config/database.php');

if (isset($_GET['id']) && isset($_GET['token']) && !empty($_GET['id']) && !empty($_GET['token'])) {
    $id = hash("sha256", base64_encode($_GET['id']));
    $token = $_GET['token'];

    $dbs = ["formular", "form", "tokens"];
    $column = ["formularid", "formularid", "token"];

    $row = [];
    $parameter = [$id, $id, $token]; // Corrected parameter values

    for ($i = 0; $i < count($dbs); $i++) {
        $sql = "SELECT * FROM " . $dbs[$i] . " WHERE `" . $column[$i] . "` = :id";
        $stmt = $dbpdo->prepare($sql);
        $stmt->execute([':id' => $parameter[$i]]);
        $row[] = ($i == 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row[$i]) {
            $temp = json_encode($row[$i]);
            header("Location: error.php?err=dberrorselect&meta=Table: {$dbs[$i]} ID: {$_GET['id']} Table: {$dbs[$i]} Token: {$_GET['token']} non_checked");
            exit;
        }
    }

    #echo json_encode($row);

    $i = count($dbs);

    $sql = "SELECT * FROM users WHERE `id` = :id"; // Use 'users' table directly
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute([':id' => $row[2]['userid']]);
    $row[$i] = $stmt->fetch(PDO::FETCH_ASSOC); // Use $i instead of $row[3]
    if (!$row[$i]) { // Check the correct index $i instead of $row[3]
        $temp = json_encode($row[$i]);
        header("Location: error.php?err=dberrorselect&meta=Table: users ID: {$_GET['id']} Table: users Token: {$_GET['token']} non_checked");
        exit;
    }

    if (empty($row[2]['token'])) {
        echo "Token does not exist";
        die();
    }

    if ($row[2]['expired'] < date("Y-m-d H:i:s")) {
        echo "Token is expired since " . $row[2]['expired'];
        die();
    }
} else {
    header("Location: error.php?err=nonparam&meta=providing_requirements: id, token");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>

<div class="header bg-black py-2">
    <div class="container" style="color: white;">
        <div style="display: flex; flex-direction: row; align-items: baseline;">
            <h1 class="me-2"><?php echo $row[1]['title']; ?></h1>
            <p>Bei/Von <?php echo base64_decode($row[3]['user']); ?></p>
        </div>
        <p class="text-secondary me-1"><?php echo $row[1]['description']; ?></p>
        <div style="display: flex; flex-direction: row; align-items: baseline;" class="py-2">
            <p class="text-secondary me-2">Token: <?php echo $row[2]['token']; ?></p>
            <p class="text-secondary me-2">ID: <?php echo $id; ?></p>
        </div>

    </div>
</div>
<div class="container">
    <form action="" method="post">
        <?php
        foreach ($row[0] as $item) {
            # echo json_encode($item);
            $required = $item['required'] == 1 ? "required" : "";
            ?>
            <div class="rounded border m-2 p-2">
                <div class="" style="display:flex; align-content: baseline">
                    <h3 class='py-2'><?php echo $item['question'] ?></h3>

                    <?php if ($required == "required") { ?>
                        <h4 class="text-danger">*</h4>
                    <?php } ?>
                </div>
                <?php
                if ($item['description']) { ?>
                    <p class='text-secondary'>Beschreibung <?php echo $item['description'] ?></p>
                <?php } ?>


                <?php
                if ($item['answertype'] == "text") {
                    include('template/text.php');
                    echo base64_encode(json_encode($_POST));
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
        <button class="btn btn-primary m-2 p-2">Absenden</button>
    </form>
</div>


<?php

/*for ($c; as $_POST[$item['id']]; $c < $_POST[$item['id']]; $c = $_POST[$item['id']) {
    echo $c;
}*/

?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>