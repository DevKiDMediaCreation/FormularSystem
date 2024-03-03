<?php
include("../config/database.php");
include("../config/fbs.php");
session_start();
global $fbsdpo;
global $dbpdo;

$stmt = $fbsdpo->prepare("SELECT `value` FROM `information` WHERE `name` = 'organization'");
$stmt->execute();
$org = $stmt->fetch();

#echo json_encode($_POST);

if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) && !empty($_POST['name'])) {

    $user = $_POST['username'];
    $pw = $_POST['password'];
    $email = $_POST['email'];
    $name = $_POST['name'];

    $user = base64_encode($user);
    $pw = hash('sha256', base64_encode($pw));

    // Use prepared statement to prevent SQL injection
    $stmt = $dbpdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch();

    if (!$row) {
        $stmt = $dbpdo->prepare("INSERT INTO users (user, pw, email, name, rights) VALUES (:user, :pw, :email, :name, 4)");
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':pw', $pw);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $status = "Registration ist erfolgreich";
    } else {
        $status = "Benutzer existiert bereits";
        #header("Location: ./login.php");
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration - Administration Unit</title>
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
<body class="bg-body-tertiary h-100 w-100 poppins-light">
<div class="container">
    <main>
        <div class="py-5 text-center">
            <h2>Admin Zugang</h2>
            <p class="lead">Jeglicher Zugang ist nur den gewährleistet, die der Organisation "<?php echo $org[0]; ?>"
                angehören und oder mitarbeiten. Der IT zugang ist nur den Administratoren vorbehalten. </p>
            <small class="text-secondary">Nur Administratoren haben die Machtgewalt 0-2. Machtgewalt 5 sind für Gäste
                und die Machtgewalt von 3 bis 4 ist den der Organisation angehörig.</small>
        </div>

        <div class="">

            <?php
            if (!empty($status)) { ?>
                <div class="<?php echo ($status == 'Registration successfully') ? 'bg-success' : 'bg-danger' ?> text-center border rounded p-2 my-2 text-white">
                    <?php echo $status; ?>
                </div>
                <?php
            }
            ?>


            <h4 class="mb-3">Registration</h4>
            <?php if ($_SESSION) { ?>
                <p>Ihre Machtgewalt als <?php echo $_SESSION['name'];?> (Benutzername: <?php echo base64_decode( $_SESSION['user']);?>) ist <?php echo $_SESSION['rights']; ?></p>
                <a href="logout.php" class="btn border btn-primary w-100 btn-lg">Logout</a>
            <?php } else { ?>
                <form class="needs-validation" novalidate="" method="post">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder=""
                                   value="<?php echo $_POST['email'] ?? null; ?>" required=""
                                   name="email">
                            <div class="invalid-feedback">
                                Nicht gültig
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="lastName" class="form-label">Passwort</label>
                            <input type="password" class="form-control" id="password" placeholder="" value=""
                                   name="password"
                                   required="">
                            <div class="invalid-feedback">
                                Nicht gültig
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="username" class="form-label">Benutzername</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control" id="username" placeholder="" required=""
                                       value="<?php echo $_POST['username'] ?? null; ?>"
                                       name="username">
                                <div class="invalid-feedback">
                                    Benutzername ist erforderlich.
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <label for="username" class="form-label">Rechtlicher Name</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">/</span>
                                <input type="text" class="form-control" id="name" placeholder="" required=""
                                       value="<?php echo $_POST['name'] ?? null; ?>"
                                       name="name">
                                <div class="invalid-feedback">
                                    Name ist erforderlich.
                                </div>
                            </div>
                            <small class="text-body-secondary">
                                (Vor- und Nachname. Wenn ein Title wie Dr. oder Prof. vorhanden ist, bitte
                                eingeben)</small>
                        </div>

                        <div class="col-12">
                            <p class="border bg-white p-2 text-center text-secondary rounded">Administration IT
                                Machtgewalt Rechte: 4 (Standard)</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="btn w-100 btn btn-primary btn-lg" type="submit">Senden</button>
                </form>
            <?php } ?>
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
