<?php

namespace App\Controller;

use \Core\Controller\Controller;

class DisplayController extends Controller
{

    public function __construct()
    {
        if ($this->security()->isConnect()) {
            $this->redirect("userProfile");
        }
        $this->loadModel('users');
        $this->loadModel('hours');
    }

    public function tactile()
    {


        $users = $this->users->all();

        foreach ($users as $user) {
            $user->presence = $this->hours->presence($user->getId());
        }
        return $this->render(
            "display/tactile",
            [
                "users" => $users
            ]
        );
    }
    public function tv()
    {
        return $this->render(
            "display/tv",
            []
        );
    }

    public function ajaxDisplayNewLine()
    {
        if (!$this->security()->login($_POST["user_email"], $_POST["pin"], true)) {
            $this->jsonResponse403();
        }

        $user = $this->session()->get("users");

        if (!$user->getVerify()) {
            $this->users->update($user->getId(), 'id', ["verify" => true]);
        }
        $this->session()->remove("users");
        $this->jsonResponse($this->hours->create(["id_users" => $user->getId()]));
    }
}
