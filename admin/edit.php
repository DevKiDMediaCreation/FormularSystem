<?php
include "../config/database.php";
include "../config/fbs.php";
session_start();

if (!$_SESSION) {
    header("Location: ./login.php");
}

if(empty($_GET['id'])) {
    header("Location: ./dashboard.php");
}

// Check if the person is the owner of the form and check simultaneously if the form exists
$form = request("SELECT * FROM form WHERE creatorid = :id AND formularid = :formid", [":id" => $_SESSION["id"], ":formid" => $_GET['id']])->fetch(PDO::FETCH_ASSOC);

if(empty($form)) {
    header("Location: ./dashboard.php");
}

echo json_encode($form);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
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
    <?php include("../template/backDashboardNav.php") ?>
    <main>
        <div class="py-5 text-center">
            <h2>Admin Zugang</h2>
            <p class="lead">Jeglicher Zugang ist nur den gewährleistet, die der Organisation
                "<?php echo getOrganization(); ?>"
                angehören und oder mitarbeiten. Der IT zugang ist nur den Administratoren vorbehalten. </p>
        </div>

        <div class="">
            <h4 class="mb-3">Configuration</h4>
            <small class="text-secondary"><?php echo $_GET['id']; ?></small>
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
                </div>

                <hr class="my-4">

                <button class="btn w-100 btn btn-primary btn-lg" type="submit">Speicherns</button>
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