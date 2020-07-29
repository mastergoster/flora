<?php

namespace App\Controller;

use \Core\Controller\Controller;

class DisplayController extends Controller
{

    public function __construct()
    {
        $this->loadModel('user');
        $this->loadModel('heures');
    }

    public function tactile()
    {
        if ($_SESSION["user"]) {
            header('Location: /user/profile');
            exit();
        }
        $users = $this->user->all();
        foreach ($users as $user) {
            $user->presence = $this->heures->presence($user->getId());
        }
        return $this->render(
            "display/tactile",
            [
                "users" => $users
            ]
        );
    }

    public function ajaxDisplayNewLine()
    {
        if ($_SESSION["user"]) {
            header('Location: /user/profile');
            exit();
        }
        $user = $this->user->connect($_POST["user_mail"], $_POST["pin"]);
        if (!$user) {
            $this->jsonResponse403();
        }
        header('content-type:application/json');
        echo json_encode($this->heures->ajout($user->getId()));
    }
}
