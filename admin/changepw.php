<?php
include("../config/database.php");
session_start();
global $dbpdo;

if (!$_SESSION) {
    header("Location: ./login.php");
}

#echo json_encode($_POST);

if (!empty($_POST['newpw']) && !empty($_POST['newpw2']) && !empty($_POST['curpw'])) {

    $newpw = $_POST['newpw'];
    $newpw2 = $_POST['newpw2'];
    $curpw = $_POST['curpw'];

    // Escape user input to prevent SQL injection

    $newpw = hash('sha256', base64_encode($newpw));
    $newpw2 = hash('sha256', base64_encode($newpw2));
    $curpw = hash('sha256', base64_encode($curpw));

    if ($newpw !== $newpw2) {
        $status = "Neues Passwort stimmt nicht überein";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $dbpdo->prepare("SELECT * FROM users WHERE user = :user AND email = :email");
        $stmt->bindParam(':user', $_SESSION['user']);
        $stmt->bindParam(':email', $_SESSION['email']);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row == null) {
            $status = "Benutzer konnte nicht gefunden werden";
            session_destroy();
        } else {
            if ($newpw === $row['pw']) {
                $status = "Neues Passwort stimmt mit dem alten Passwort überein";
            } else {
                if ($curpw !== $row['pw']) {
                    $status = "Password übereinstimmt nicht dem den Passwort bei der Registrierung";
                    session_destroy();
                } else {
                    $stmt = $dbpdo->prepare("UPDATE users SET pw = :newpw WHERE user = :user AND email = :email");
                    $stmt->bindParam(':newpw', $newpw);
                    $stmt->bindParam(':user', $_SESSION['user']);
                    $stmt->bindParam(':email', $_SESSION['email']);
                    $stmt->execute();


                    $_SESSION['pw'] = $row['pw'];
                    $status = "Änderung ist erfolgreich.";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Administration Unit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">

    <link href="../assets/font.css" rel="stylesheet">

</head>
<body class="bg-body-tertiary h-100 w-100 poppins-light">
<div class="container">
    <main>
        <div class="py-5 text-center">
            <h2>Admin Zugang</h2>
            <p class="lead">Jeglicher Zugang ist nur den gewährleistet, die der Organisation "Otto-Hahn-Schule Hanau"
                angehören und oder mitarbeiten. Der IT zugang ist nur den Administratoren vorbehalten. </p>
            <small class="text-secondary">Nur Administratoren haben die Machtgewalt 0-2. Machtgewalt 5 sind für Gäste
                und die Machtgewalt von 3 bis 4 ist den der Organisation angehörig.</small>
        </div>

        <div class="">

            <?php
            if (isset($status) && !empty($status)) { ?>
                <div class="<?php echo ($status == 'Änderung ist erfolgreich.') ? 'bg-success' : 'bg-danger' ?> border rounded p-2 my-2 text-white">
                    <?php echo $status; ?>
                </div>
                <?php
            }
            ?>


            <h4 class="mb-3">Passwort ändern</h4>
            <p>Ihre Machtgewalt als <?php echo $_SESSION['name']; ?>
                (Benutzername: <?php echo base64_decode($_SESSION['user']); ?>)
                ist <?php echo $_SESSION['rights']; ?></p>
            <form class="needs-validation" novalidate="" method="post">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="firstName" class="form-label">Neues Passwort</label>
                        <input type="password" class="form-control" id="newpw" placeholder=""
                               value="<?php echo isset($_POST['newpw']) ? $_POST['newpw'] : null; ?>"
                               name="newpw"
                               required="">
                        <div class="invalid-feedback">
                            Nicht gültig
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="lastName" class="form-label">Neues Passwort bestätigen</label>
                        <input type="password" class="form-control" id="newpw2" placeholder="" value=""
                               name="newpw2"
                               required="">
                        <div class="invalid-feedback">
                            Nicht gültig
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="username" class="form-label">Derzeitiges Passwort</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">*</span>
                            <input type="password" class="form-control" id="curpw" placeholder="" required="" value=""
                                   name="curpw">
                            <div class="invalid-feedback">
                                Altes Passwort ist erforderlich für die Änderung.
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <button class="btn w-100 btn btn-primary btn-lg" type="submit">Senden</button>
            </form>
            <a href="logout.php" class="btn border btn-primary w-100 btn-lg my-2">Logout</a>
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
