<?php
global $dbpdo;
include('config/database.php');

if (isset($_GET['id']) && isset($_GET['token']) && !empty($_GET['id']) && !empty($_GET['token'])) {
    // Request
    $id = hash("sha256", base64_encode($_GET['id']));
    $token = $_GET['token'];

    $sql = "SELECT * FROM formular WHERE `formularid` = :id";
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$row) {
        $temp = json_encode($row);
        header("Location: error.php?err=dberrorselect&meta=Table: {$temp} ID: {$_GET['id']} Table: formular Token: {$_GET['token']} non_checked");
    }

    $sql = "SELECT * FROM form WHERE `formularid` = :id";
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $rowForm = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM tokens WHERE `token` = :token";
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute([':token' => $token]);
    $rowToken = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($rowToken['token'])) {
        echo "Token does not exist";
    }

    if ($rowToken['expired'] < date("Y-m-d H:i:s")) {
        echo "Token is expired since " . $rowToken['expired'];
        die();
    }

    $sql = "SELECT * FROM users WHERE `id` = :id";
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute([':id' => $rowForm['creatorid']]);
    $creator = $stmt->fetch(PDO::FETCH_ASSOC);

} else {
    header("Location: error.php?err=nonparam&meta=providing_requirements: id, token");
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
            <h1 class="me-2"><?php echo $rowForm['title']; ?></h1>
            <p>Bei/Von <?php echo base64_decode($creator['user']); ?></p>
        </div>
        <p class="text-secondary me-2"><?php echo $rowForm['description']; ?></p>
        <div style="display: flex; flex-direction: row; align-items: baseline;" class="py-2">
            <p class="text-secondary me-2">Token: <?php echo $rowToken['token']; ?></p>
            <p class="text-secondary me-2">ID: <?php echo $id; ?></p>
        </div>

    </div>
</div>
<div class="container">
    <form action="" method="post">
        <?php foreach ($row as $item) {
            $required = $item['required'] == "true" ? "required" : "";
            ?>

            <div class="rounded border m-2 p-2">
                <div class="" style="display:flex; align-content: baseline">
                    <h3 class='py-2'><?php echo $item['question'] ?></h3>

                    <?php if ($required == "required") { ?>
                        <h4 class="text-danger">*</h4>
                    <?php } ?>
                </div>


                <?php
                if ($item['answertype'] == "text") { ?>
                    <div class="input-group-text">
                        <input type="text" id="<?php echo $item['id']; ?>" placeholder="<?php echo $item['meta']; ?>"
                               name='text' class='border form-control rounded p-2' required="<?php echo $required; ?>">
                        <!-- border-bottom border-0-->
                    </div>
                    <?php
                    // Get the value of the input

                    if (isset($_POST[$item['id']])) {
                        echo $_POST[$item['id']];
                    }
                    echo json_encode($_POST);

                } elseif ($item['answertype'] == "star") {
                    $item['meta'] = max(3, min(10, $item['meta']));
                    ?>
                    <div class="input-group-text btn-group" role="group" style="display: flex; justify-content: center">
                        <?php
                        for ($i = 0; $i <= $item['meta'] - 1; $i++) { ?>
                            <button type="button" class="border btn" data-bs-toggle="button">
                                <!--<input type="radio" id="<?php echo $i; ?>-stars" name="rating" value="<?php echo $i; ?>"/>
                        --><label for="<?php echo $i; ?>-stars" class="star p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                         class="bi bi-star" viewBox="0 0 16 16">
                                        <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                                    </svg>
                                </label>
                            </button>
                            <!--&#9733; Star-->
                        <?php } ?>
                    </div>
                <?php } elseif ($item['answertype'] == "read") { ?>
                    <div class="input-group-text">
                <textarea id="<?php echo $item['id']; ?>" placeholder="<?php echo $item['meta']; ?>"
                          name='text' class='border form-control rounded p-2'></textarea>
                        <!-- border-bottom border-0-->
                    </div>
                <?php } elseif ($item['answertype'] == "select") {
                    $choices = explode(";", $item['meta']);
                    ?>
                    <div class="input-group-text">
                        <select id="<?php echo $item['id']; ?>" class="form-select" aria-label="Default select example">
                            <option selected>Selectiere</option>
                            <?php foreach ($choices as $choice) { ?>
                                <option value="<?php echo $choice; ?>"><?php echo $choice; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <?php
                } elseif ($item['answertype'] == "choice") {
                    // Radio
                    $choices = explode(";", $item['meta']);
                    ?>
                    <?php foreach ($choices as $choice) { ?>
                        <input type="radio" class="btn-check" name="<?php echo $item['id'] . $choice; ?>"
                               id="<?php echo $item['id'] . $choice; ?>">
                        <label for="<?php echo $item['id'] . $choice; ?>"
                               class="btn btn-outline-black"><?php echo $choice; ?></label>
                    <?php }
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