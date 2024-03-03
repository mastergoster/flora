<?php

namespace App\Controller\Api;

use App\Model\Table\ImagesTable;
use App\Model\Table\RessourcesTable;
use \Core\Controller\Controller;

class ApiRessourcesController extends Controller
{
    protected RessourcesTable $ressources;
    protected ImagesTable $images;

    public function __construct()
    {
        $this->loadModel('ressources');
        $this->loadModel('images');
    }

    public function all()
    {
        $ressources = $this->ressources->all();
        $return = [];
        foreach ($ressources as $ressource) {
            $ressource->image = "/medias/" .  $this->images->find($ressource->getIdImages())->getName();
            $return[] = $ressource->__toArray();
        }

        return $this->jsonResponse(
            [
                "ressources" => $return
            ]
        );
    }
}
