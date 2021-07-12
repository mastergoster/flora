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
    }

    public function init(): Response
    {
        $datas = $this->request()->request;
        $user = $this->session()->get('users');
        if ($datas->get("id_user") == $user->getId() && $datas->get("function") == "updatepicture") {
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
        return $this->jsonResponse([]);
    }
}
