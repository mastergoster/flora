<?php
session_start();
include "class/Heures.php";
include 'class/Adherant.php';

$adherants = new Adherant();
$heures = new Heures();


//verifier utilisateur
if (

    isset($_POST['id_user']) &&
    (isset($_POST['pin']) ||
        $_SESSION["admin"] == $_POST['id_user'] ||
        $_SESSION["admin"] == "admin") &&
    $_GET['action'] == "newline"
) {




    $adherant = $adherants->listById($_POST['id_user']);

    if (!$adherant) {
        header('HTTP/1.0 403 Forbidden');
        header('Content-Type: application/json');
        echo json_encode(["permission" => "refusé"]);
        exit();
    };
    if (isset($_POST['pin']) && $_POST['pin'] != $adherant["pin"]) {
        header('HTTP/1.0 403 Forbidden');
        header('Content-Type: application/json');
        echo json_encode(["permission" => "refusé"]);
        exit();
    };
    $return = $heures->ajout($adherant['id']);
    header('Content-Type: application/json');
    echo json_encode($return);
    exit();
};

if ($_SESSION['admin'] == "admin") {
    $admin = true;
} else {
    header('HTTP/1.0 403 Forbidden');
    header('Content-Type: application/json');
    echo json_encode(["permission" => "refusé"]);
    exit();
};

if (
    isset($_POST['id']) &&
    $_GET['action'] == "del"
) {
    $return = $heures->del($_POST['id']);
    header('Content-Type: application/json');
    echo json_encode($return);
    exit();
};
