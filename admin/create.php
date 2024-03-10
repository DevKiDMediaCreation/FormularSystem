<?php
global $dbpdo;
include "../config/database.php";
include "../utils/tokens.php";
include "../utils/randomString.php";
$status = "";

$token = generateAnonymousToken(
    RandomString(rand(4, 40)), rand(3, 299));
$expired = date("Y-m-d H:i:s", strtotime("+1 month"));

if (!$_SESSION) {
    header("Location: ./login.php");
}

if ($_POST) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $thm = $_POST['thm'];
    $cls = $_POST['cls'];
    $scf = $_POST['scf'];
    $ccb = $_POST['ccb'];
    $nmdb = $_POST['nmdb'];
    $thm = $_POST['thm'];
    $pwdb = hash('sha256', base64_encode($_POST['pwdb']));
    $visibility = $_POST['visibility'];
    $expired = $_POST['expired'];

    if ($expired < date("Y-m-d H:i:s")) {
        $status = "Verfall ist nicht gültig";
    }

    $sql = "SELECT * FROM users WHERE name = :name";
    $stmt = $dbpdo->prepare($sql);
    $stmt->execute([':name' => $nmdb]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $status = "Name ist nicht gültig";
    } else {
        if ($user['pw'] != $pwdb) {
            $status = "Passwort ist nicht gültig";
        } else {
            // Check if the token is alread exist. If yes then regenerate the token.
            $sql = "SELECT * FROM form WHERE formularid = :token";
            $stmt = $dbpdo->prepare($sql);
            $stmt->execute([':token' => $token]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $token = generateAnonymousToken(RandomString(rand(4, 40)), rand(3, 299));
            }

            $meta = $cls . ";" . $scf . ";" . $ccb . ";" . $visibility;
            $now = date('Y-m-d H:i:s');

            $sql = "INSERT INTO form (title, description,formularid, created, expired, meta, creatorid, subject) VALUES (:name, :description, :token, :now, :expired, :meta, :id, :thm)";
            $stmt = $dbpdo->prepare($sql);
            $stmt->execute([':name' => $name, ':description' => $description, ':token' => $token, ':expired' => $expired, ':meta' => $meta, ':now' => $now, 'id' => $user['id'], ':thm' => $thm]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $files = ["dataprivacy", "license", "ano", "impressum"];
            $index = [0, 2, 3, 1];
            $names = ["Datenschutzeinverständniserklärung", "Lizenz", "Anonymität", "Impressum"];

            for ($i = 0; $i < count($files); $i++) {
                $sql = "INSERT INTO `formular` (`question`, `answertype`, `required`, `formularid`, `index`, `group`, `page`, `description`, `meta`, `write`)
                        VALUES (:name, 'read', b'1', :token, :index, 1, 0, NULL, :text, b'0')";
                $stmt = $dbpdo->prepare($sql);
                $text = file_get_contents("assets/" . $files[$i]);
                $stmt->execute([':name' => $names[$i], ':token' => $token, ':text' => $text, ':index' => $index[$i]]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $status = "Auftrag ist erfolgreich gesendet worden.";

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
    <main>
        <div class="py-5 text-center">
            <h2>Formular erstellen</h2>
            <p class="lead">Jedes Formular/Feedback entsteht hier. Noch nur ein Schritt bis zur Perfektion.</p>
            <small class="text-secondary">Nur Administratoren haben die Machtgewalt 0-2. Machtgewalt 5 sind für Gäste
                und die Machtgewalt von 3 bis 4 ist den der Organisation angehörig.</small>
        </div>
        <?php
        if (!empty($status)) { ?>
            <div class="bg-danger text-center border rounded p-2 my-2 text-white">
                <?php echo $status; ?>
            </div>
            <?php } ?>


        <div class="">
            <h3 class="mb-3">Formular</h3>
            <form class="needs-validation" novalidate="" method="post">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="name    " class="form-label">Name d. Formular</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder=""
                               value="<?php echo $_POST['name'] ?? null; ?>"
                               required="">
                        <div class="invalid-feedback">
                            Title ist erforderlich.
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="description" class="form-label">Beschreibung d. Formular</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder=""
                               value="<?php echo $_POST['description'] ?? null; ?>"
                               required="">
                        <div class="invalid-feedback">
                            Beschreibung ist erforderlich.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="thm" class="form-label">Thema/Fach und Klasse</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control" id="thm" name="thm" placeholder="" required=""
                                   value="<?php echo $_POST['thm'] ?? null; ?>">
                            <div class="invalid-feedback">
                                Thema/Fach und Klasse sind erforderlich.
                            </div>
                            <!--Non required-->
                        </div>
                    </div>

                    <!--Later general with DB fbs-->
                    <div class="col-md-5">
                        <label for="cls" class="form-label">Klassenstufe/Jahr/Semester</label>
                        <select class="form-select" id="cls" required="" name="cls">
                            <option value="">Wählen...</option>
                            <?php for ($i = 1; $i <= 13; $i++) { // 20
                                echo "<option value='{$i}'>$i</option>";
                            } ?>
                            <option value='unterstufe'>Unterstufe (Kl. 5-6)</option>
                            <option value='mittelstufe'>Mittelstufe (Kl. 7- 10)</option>
                            <option value='oberstufe'>Oberstufe/Abitur (Kl. 11-12(13)) </option>
                        </select>
                        <div class="invalid-feedback">
                            Wählen sie eine Klassenstufe.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="scf" class="form-label">Schulform/Bildungsform</label>
                        <select class="form-select" id="scf" required="" name="scf">
                            <option value="">Wählen...</option>
                            <option value="gymnasium">Gymnasium</option>
                            <option value="realschule">Realschule</option>
                            <option value="hauptschule">Hauptschule</option>
                            <option value="gesamtschule">Gesamtschule</option>
                            <option value="grundschule">Grundschule</option>
                            <option value="arbeitsschule">Arbeitsschule</option>
                            <option value="waldorfschule">Waldorfschule</option>
                            <option value="montessorischule">Montessori schule</option>
                            <option value="freieschule">Freie Schule</option>
                            <option value="academy">Academy</option>
                            <option value="internationaleschule">Internationale Schule</option>
                            <option value="europaschule">Europaschule</option>
                            <option value="privatschule">Privatschule</option>
                            <option value="universität">Universität</option>
                            <option value="fachhochschule">Fachhochschule</option>
                            <option value="berufsschule">Berufsschule</option>
                            <option value="abendschule">Abendschule</option>
                            <option value="abitur">Abitur</option>
                            <option value="onlineschule">Online Schule</option>
                            <option value="andere">Andere</option>
                        </select>
                        <div class="invalid-feedback">
                            Bitte wählen sie eine Schulform aus.
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="ccb" class="form-label">Klassenbuchstabe</label>
                        <input type="text" class="form-control" id="ccb" placeholder="A..Z" required=""
                               maxlength="1" name="ccb"
                               spellcheck="false" data-ms-editor="true"
                               value="<?php echo $_POST['ccb'] ?? null; ?>">
                        <div class="invalid-feedback">
                            Klassenidentität ist erforderlich.
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <p class="border rounded p-2 bg-danger text-white">Beim nicht wählen, wird automatich das Formular
                    für die ganze Organization sichtbar sein.</p>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="class" value="class">
                    <label class="form-check-label" for="class">
                        Innerhalb nur der Klasse/Kurs
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="stufe" value="stufe">
                    <label class="form-check-label" for="stufe">
                        Innerhalb nur der Stufe
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="org" checked value="org">
                    <label class="form-check-label" for="org">
                        Innerhalb der ganzen Organisation
                    </label>
                </div>


                <hr class="my-4">

                <h4 class="mb-3">Token erstellung</h4>
                <div class="my-3 m-2">
                    <div class="">
                        <p class="border rounded p-2 my-2 bg-white text-center"><?php echo $token; ?></p>
                        <label class="form-label" for="expired">Formularverfall (Standard gemäß 1
                            Monat.)</label>

                        <input type="datetime-local" name="expired" class="form-control" id="expired"
                               value="<?php echo $expired; ?>">

                        <p class="border rounded my-2 p-2 bg-white text-center">Antrag
                            am: <?php echo date("Y-m-d H:i:s"); ?></p>
                    </div>
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

                <button class="w-100 btn btn-primary btn-lg" type="submit">Senden</button>
            </form>
        </div>
    </main>

    <footer class="my-5 pt-5 text-body-secondary text-center text-small">
        <p class="mb-1">© 2019–2024 by Duy Nam Schlitz</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script src="../assets/require.js"></script>

</body>
</html>