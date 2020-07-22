<?php
session_start();
include 'class/Adherant.php';
include 'config.php';

if ($_SESSION['admin'] == "admin") {
    header('Location: admin.php');
    exit();
} elseif ($_SESSION['admin']) {
    header('Location: profile.php?id=' . $_SESSION['admin']);
    exit();
}

$adherants = new Adherant();

if (!empty($_POST)) {
    if ($_POST["email"] == $usernameadmin && $_POST["password"] == $passwordadmin) {
        $_SESSION["admin"] = "admin";
        $id = 1;
        header('Location: admin.php');
        exit();
    } else {

        $ad =  $adherants->listByEmail($_POST["email"]);
        if (!$ad) {
            header('Location: /');
            exit();
        }
        if (!password_verify($_POST["password"], $ad["mdp"])) {
            header('Location: /');
            exit();
        }
        $_SESSION["admin"] = $id = $ad["id"];
    }
    header('Location: profile.php?id=' . $id);
    exit();
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>CoWorkInMoulins</title>


    <!-- Bootstrap core CSS -->
    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">


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
    </style>
    <link href="css/style2.css" rel="stylesheet">

</head>

<body>
    <form class="form-signin" method="post">
        <div class="text-center mb-4">
            <img class="mb-4" src="/docs/4.5/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Connexion</h1>
            <p>CoWorkInMoulins</p>
        </div>

        <div class="form-label-group">
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
            <label for="inputEmail">Email address</label>
        </div>

        <div class="form-label-group">
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <label for="inputPassword">Password</label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted text-center">&copy; 2017-2020</p>
    </form>
</body>

</html>