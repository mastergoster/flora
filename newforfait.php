<?php
session_start();
include "class/Forfait.php";


if (!isset($_SESSION['admin'])) {
    header('HTTP/1.0 403 Forbidden');
    header('Content-Type: application/json');
    echo json_encode(["permission" => "refusé"]);
    exit();
}
if ($_SESSION['admin'] == "admin") {
    $admin = true;
} else {
    header('HTTP/1.0 403 Forbidden');
    header('Content-Type: application/json');
    echo json_encode(["permission" => "refusé"]);
    exit();
}

$forfait = new Forfait();

//creation nouveau forfait
if (
    $_GET["action"] == "new" &&
    isset($_POST['nom']) &&
    isset($_POST['prix']) &&
    isset($_POST['duree'])
) {
    $return = $forfait->create($_POST["nom"], $_POST["prix"], $_POST["duree"]);
    header('Content-Type: application/json');
    echo json_encode($return);
    exit();
}


//ajout d'un nouveau forfait

if (
    $_GET["action"] == "ajout" &&
    isset($_POST['id_user']) &&
    isset($_POST['id_forfait']) &&
    isset($_POST['cearted_at'])
) {

    $return = $forfait->ajout($_POST["id_user"], $_POST["id_forfait"], $_POST["cearted_at"]);
    header('Content-Type: application/json');
    echo json_encode($return);
    exit();
}

//suppression d'un forfait

if (
    $_GET["action"] == "del" &&
    isset($_POST['id'])
) {

    $return = $forfait->del($_POST["id"]);
    header('Content-Type: application/json');
    echo json_encode($return);
    exit();
}
