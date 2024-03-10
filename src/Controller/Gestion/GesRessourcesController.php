<?php

namespace App\Controller\Gestion;

use \Core\Controller\Controller;
use App\Model\Table\ImagesTable;
use App\Model\Entity\ImagesEntity;
use Core\Controller\FormController;
use App\Model\Table\RessourcesTable;
use Core\Controller\FilesController;

class GesRessourcesController extends Controller
{
    protected RessourcesTable $ressources;
    protected ImagesTable $images;

    public function __construct()
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $this->loadModel('images');
        $this->loadModel('ressources');
    }

    public function all()
    {
        $items = $this->ressources->all();
        foreach ($items as $item) {
            $item->image = $this->images->find($item->getIdImages());
        }

        return $this->render(
            "bureau/ressources",
            [
                "items" => $items
            ]
        );
    }

    public function modif($id)
    {
        $product = $this->ressources->find($id);
        if (!$product) {
            $id = null;
        }
        $form = new FormController();
        $form->field("name", ["require"]);
        $form->field("slug", ["require"]);
        $form->field("description", ["require"]);
        $form->field("nombrePlace", ["require", "int"]);
        $form->field("color", ["require"]);
        $errors =  $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            if (!$errors) {
                if ($id === null) {
                    $filemanager = new FilesController(getenv("PATH_BASE") . \DIRECTORY_SEPARATOR . "html");
                    $name = $filemanager->uploadFile("medias");
                    $images = new ImagesEntity();
                    $images
                        ->setRef($name[0]["folder"])
                        ->setName($name[0]["name"])
                        ->setDesc("photo de la salle {$datas['name']}");
                    $this->images->create($images, true);
                    $datas["id_images"] = $this->images->lastInsertId();
                    $datas["nombre_place"] = $datas["nombrePlace"];
                    unset($datas["nombrePlace"]);
                    $product = $this->ressources->create($datas);
                } else {
                    $product->hydrate($datas);
                    $this->ressources->updateByClass($product);
                }
            }
        }

        return $this->render(
            "bureau/ressourcesEdit",
            [
                "item" => $product
            ]
        );
    }
}
