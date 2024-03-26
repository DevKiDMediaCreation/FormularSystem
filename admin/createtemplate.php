<?php
global $dbpdo;
include "../config/database.php";
include "../utils/tokens.php";
include "../utils/randomString.php";
$status = "";

session_start();

$token = generateAnonymousToken(
    RandomString(rand(4, 40)), rand(3, 299));

if (!$_SESSION) {
    header("Location: ./login.php");
}

if ($_POST) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $nmdb = $_POST['nmdb'];
    $pwdb = hash('sha256', base64_encode($_POST['pwdb']));

    $user = request("SELECT * FROM users WHERE name = :name", [':name' => $nmdb])->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $status = "Name ist nicht gültig";
    } else {
        if ($user['pw'] != $pwdb) {
            $status = "Passwort ist nicht gültig";
        } else {
            // Check if the token is alread exist. If yes then regenerate the token.
            $row = request("SELECT * FROM form WHERE formularid = :token", [':token' => $token])->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $token = generateAnonymousToken(RandomString(rand(4, 40)), rand(3, 299));
            }

            $sql = "INSERT INTO form (title, description, formularid, created, creatorid, type, visibility) VALUES (:name, :description, :formularid, NOW(), :id, :type, 'public')";
            $params = [':name' => $name, ':description' => $description, ':formularid' => $token, ':id' => $user['id'], ':type' => 'template'];
            $row = request($sql, $params)->fetch(PDO::FETCH_ASSOC);

            $files = ["dataprivacy", "license", "ano", "impressum"];
            $index = [0, 2, 3, 1];
            $names = ["Datenschutzeinverständniserklärung", "Lizenz", "Anonymität", "Impressum"];

            for ($i = 0; $i < count($files); $i++) {
                $sql = "INSERT INTO `formular` (`question`, `answertype`, `required`, `formularid`, `index`, `group`, `page`, `description`, `meta`, `write`)
                        VALUES (:name, 'read', b'1', :token, :index, 1, 0, NULL, :text, b'0')";
                $stmt = $dbpdo->prepare($sql);
                $stmt->execute([':name' => $names[$i], ':token' => $token, ':text' => file_get_contents("assets/" . $files[$i]), ':index' => $index[$i]]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $status = "Auftrag ist erfolgreich gesendet worden.";
            header("Location: edit.php?id=$token");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/font.css">
    <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="bg-body-tertiary poppins-light break">
<div class="container">
    <?php include("../template/backDashboardNav.php") ?>
    <main>
        <div class="py-5 text-center">
            <h2>Vorlage erstellen</h2>
            <p class="lead">Jede Vorlage/Feedback entsteht hier. Noch nur ein Schritt bis zur Perfektion.</p>
        </div>
        <?php
        if (!empty($status)) { ?>
            <div class="bg-danger text-center border rounded p-2 my-2 text-white">
                <?php echo $status; ?>
            </div>
        <?php } ?>


        <div class="">
            <h3 class="mb-3">Vorlage</h3>
            <form class="needs-validation" novalidate="" method="post">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="name    " class="form-label">Name d. Vorlage</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder=""
                               value="<?php echo $_POST['name'] ?? null; ?>"
                               required="">
                        <div class="invalid-feedback">
                            Title ist erforderlich.
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <label for="thm" class="form-label">Beschreibung</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control" id="description" name="description" placeholder=""
                                   required=""
                                   value="<?php echo $_POST['description'] ?? null; ?>">
                            <div class="invalid-feedback">
                                Thema/Fach und Klasse sind erforderlich.
                            </div>
                            <!--Non required-->
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Token erstellung</h4>
                <div class="my-3 m-2">
                    <p class="border rounded p-2 my-2 bg-white text-center"><?php echo $token; ?></p>
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Verifikation</h4>

                <p class="text-secondary my-2">
                    Benutzer: <?php echo hash('sha256', base64_encode($_SESSION['user'] . $_SESSION['pw'])); ?></p>


                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="nmdb" class="form-label">Name d. Herausgeber*innen</label>
                        <input type="text" class="form-control" id="nmdb" name="nmdb" placeholder="" required=""
                               spellcheck="false" data-ms-editor="true"
                               value="<?php echo $_POST['nmdb'] ?? null; ?>">
                        <small class="text-body-secondary">Geben Sie den Namen ein, den Sie bei dem Registrieren
                            genutzt haben. Es wird überprüft, ob Sie den richtigen Namen
                            eingegeben haben.</small>
                        <div class="invalid-feedback">
                            Name d. Herausgeber*innen ist erforderlich
                        </div>
                    </div>
                    <!--Check the name and if any ID exist then show.-->

                    <div class="col-md-6">
                        <label for="pwdb" class="form-label">Password d. Herausgeber*innen</label>
                        <input type="password" class="form-control" id="pwdb" name="pwdb" placeholder="" required=""
                               spellcheck="false" data-ms-editor="true">
                        <small class="text-body-secondary">Alle sensible Daten wurden verschlüsselt und verhash.
                            Es seie unmöglich dem Passwort zu hacken, außer bei schwachen Passwörter</small>
                        <div class="invalid-feedback">
                            Passwort d. Herausgeber*innen
                        </div>
                    </div>

                </div>
                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit">Erstellen</button>
            </form>
        </div>
    </main>

    <?php include "../template/footer.php" ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script src="../assets/require.js"></script>

</body>
</html>