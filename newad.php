<?php
session_start();

include 'class/Adherant.php';
include "class/Sms.php";
$sms = new Sms();
if (!isset($_SESSION['admin'])) {
    header('Location: /');
    exit();
}
if ($_SESSION['admin'] == "admin") {
    $admin = true;
} else {
    die("403");
}


$adherants = new Adherant();

if (
    !empty($_POST) &&
    !empty($_POST["firstName"]) &&
    !empty($_POST["lastName"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["tel"]) &&
    !empty($_POST["mdp"]) &&
    !empty($_POST["pin"])
) {

    $last = $adherants->create(
        $_POST["lastName"],
        $_POST["firstName"],
        $_POST["email"],
        $_POST["mdp"],
        $_POST["pin"],
        $_POST["tel"]
    );
    $sms->numero($_POST["tel"])->send("Bonjour " . $_POST["lastName"] . " \nBienvenu a l'espace de coworking de moulins,\nvotre code pin pour la borne est : " . $_POST["pin"] . "\nPour vous connecter au panel web https://local.coworkinmoulins.fr\nvotre identifiant est : " . $_POST["email"] . "\nCordialement");
    header('Location: profile.php?id=' . $last);
    exit();
};
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Checkout example · Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/checkout/">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/4.5/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon.ico">
    <meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .container {
            max-width: 960px;
        }

        .lh-condensed {
            line-height: 1.25;
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="/docs/4.5/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
            <h2>CoWorkInMoulins</h2>
            <p class="lead">Nouvelle utilisateur.</p>
        </div>

        <div class="row">

            <div class="col-md-12">
                <form class="needs-validation" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">Nom</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Nom" value="" required>
                            <div class="invalid-feedback">
                                Valid first name is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Prenom</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Prenom" value="" required>
                            <div class="invalid-feedback">
                                Valid last name is required.
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                        <div class="invalid-feedback">
                            Please enter a valid email address for shipping updates.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tel">Portable</label>
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="06....." required>
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mdp">mot de passe</label>
                        <input type="mdp" class="form-control" id="mdp" name="mdp" placeholder="Apartment or suite" required>
                    </div>
                    <div class="mb-3">
                        <label for="pin">pin</label>
                        <input type="text" class="form-control" id="pin" name="pin" placeholder="Apartment or suite" required>
                    </div>

                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Crée</button>
                </form>
            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2017-2020 CoWorkInMoulins</p>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>
        window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')
    </script>
    <script src="/docs/4.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    <script src="form-validation.js"></script>
</body>

</html>