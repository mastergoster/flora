<?php

namespace App\Controller;

use App\Model\Entity\ImagesEntity;
use \Core\Controller\Controller;
use Core\Controller\FilesController;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->loadModel('users');
        $this->loadModel('images');
        $this->loadModel('hours');
    }

    public function init(): Response
    {

        $datas = $this->request()->request;
        $user = $this->session()->get('users');
        if ($datas->get("function") == "updatepicture") {
            if ($datas->get("id_user") == $user->getId()) {
                $filemanager = new FilesController(getenv("PATH_BASE") . \DIRECTORY_SEPARATOR . "html");
                $name = $filemanager->uploadFile("medias");
                $images = new ImagesEntity();
                $images
                    ->setRef($name[0]["folder"])
                    ->setName($name[0]["name"])
                    ->setDesc("photo de profil de {$user->getLastname()} {$user->getFirstname()}");
                $this->images->create($images, true);
                $this->users->update($user->getId(), "id", ["id_images" => $this->images->lastInsertId()]);

                return $this->jsonResponse([
                    "url" => "/{$name[0]['folder']}/{$name[0]['name']}"
                ]);
            }
        } elseif ($datas->get("function") == "tactileusers") {
            $render = new \stdClass();

            $users = $this->users->alldisplay('0001', true, "last_name");
            foreach ($users as $user) {
                $userstd = new \stdClass();
                $userstd->id = $user->getId();
                $userstd->firstname = $user->getFirstName();
                $userstd->email = $user->getEmail();
                $userstd->lastname = $user->getLastName();
                $user->presence = $this->hours->presence($user->getId());
                if ($user->presence = $this->hours->presence($user->getId())) {
                    $userstd->presence = true;
                } else {
                    $userstd->presence = false;
                }
                $render->{$user->getId()} = $userstd;
            }
            return $this->jsonResponse($render);
        }
        return $this->jsonResponse(["oups"]);
    }
}
