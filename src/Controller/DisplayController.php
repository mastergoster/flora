<?php

namespace App\Controller;

use \Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DisplayController extends Controller
{

    public function __construct()
    {
        if ($this->security()->isConnect()) {
            $this->redirect("userProfile")->send();
            exit();
        }
        $this->loadModel('users');
        $this->loadModel('hours');
    }

    public function tactile(): Response
    {
        $presence = [];
        $absent = [];
        $users = $this->users->alldisplay('0001', true, "last_name");
        foreach ($users as $user) {
            $user->presence = $this->hours->presence($user->getId());
            if ($user->presence = $this->hours->presence($user->getId())) {
                $presence[] = [
                    "id" => $user->getId(),
                    "presence" => $user->presence,
                    "email" => $user->getEmail(),
                    "firstName" => $user->getFirstName(),
                    "lastName" => $user->getLastName(),
                ];
            } else {
                $absent[] = [
                    "id" => $user->getId(),
                    "presence" => $user->presence,
                    "email" => $user->getEmail(),
                    "firstName" => $user->getFirstName(),
                    "lastName" => $user->getLastName(),
                ];
            }
        }
        return $this->render(
            "display/tactile",
            [
                "users" => array_merge($presence, $absent)
            ]
        );
    }

    public function tv(): Response
    {
        return $this->render(
            "display/tv",
            []
        );
    }

    public function ajaxDisplayNewLine(): Response
    {
        if (!$this->security()->login($_POST["user_email"], $_POST["pin"], true)) {
            return $this->jsonResponse403();
        }

        $user = $this->users->find($_POST['user_email'], 'email');

        if (!$user->getVerify()) {
            $this->users->update($user->getId(), 'id', ["verify" => true]);
        }
        return $this->jsonResponse($this->hours->create(["id_users" => $user->getId()]));
    }
}
