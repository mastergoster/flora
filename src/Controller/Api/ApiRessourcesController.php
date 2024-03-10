<?php

namespace App\Controller\Api;

use App\Model\Table\BooksTable;
use \Core\Controller\Controller;
use App\Model\Table\ImagesTable;
use App\Model\Entity\BooksEntity;
use App\Model\Table\RessourcesTable;

class ApiRessourcesController extends Controller
{
    protected RessourcesTable $ressources;
    protected ImagesTable $images;
    protected BooksTable $books;

    public function __construct()
    {
        $this->loadModel('ressources');
        $this->loadModel('images');
        $this->loadModel('books');
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

    public function book()
    {
        
        // [
        //     "title"=> 'Testxcwc',
        //     "start"=> '2024-03-07T10:30:00',
        //     "end"=> '2024-03-07T11:30:00',
        //     "extendedProps"=> [
        //       "salle"=> 'Testxcwc',
        //       "editable"=> false,
        //       "idBdd"  => 1,
        //     ],
        //     "backgroundColor"=> 'rgba(255, 0, 0, 0.5)',
        //     "editable"=> false
        // ],
        $user = $this->session()->get('users');
        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);
        if ($user == null) {
            return $this->jsonResponse(
                [
                    "message" => "Forbidden"
                ],
                403
            );
        }
        if($data['start'] == null || $data['end'] == null || $data['salle'] == null){
            return $this->jsonResponse(
                [
                    "message" => "Bad request"
                ],
                400
            );
        }
        if((new \DateTime($data['start']))->modify("30 min")->format('Y-m-d H:i:s') < date('Y-m-d H:i:s')){
            return $this->jsonResponse(
                [
                    "message" => "Bad request"
                ],
                400
            );
        }
        if($data['start'] > $data['end']){
            return $this->jsonResponse(
                [
                    "message" => "Bad request"
                ],
                400
            );
        }
        if($data['delete']){
            $book = $this->books->find($data['idBdd']);
            if($book->getIdUser() != $user->getId()){
                return $this->jsonResponse(
                    [
                        "message" => "Forbidden"
                    ],
                    403
                );
             $this->books->delete($data['idBdd']);
            return $this->jsonResponse(
                [
                    "message" => "Deleted",
                    "id" => $data['idBdd']
                ],
                200
            );
        }
        if(count($this->books->verify($data['salle'], $data['start'], $data['end'], $data['idBdd'])) > 0){
            return $this->jsonResponse(
                [
                    "message" => "Forbidden"
                ],
                403
            );
        }
        if($data['idBdd']){
            $books = $this->books->find($data['idBdd']);
            if($books->getIdUser() != $user->getId()){
                return $this->jsonResponse(
                    [
                        "message" => "Forbidden"
                    ],
                    403
                );
            }
            $books->setStartAt($data['start']);
            $books->setEndAt($data['end']);
            $books->setIdUser($user->getId());
            $ressource = $this->ressources->find($data['salle'], 'slug');
            $books->ressource_slug = $ressource->getSlug();
            $books->ressource_color = $ressource->getColor();
            $books->ressource_name = $ressource->getName();

            $books->setUpdatedAt(date('Y-m-d H:i:s'));
            $this->books->updateByClass($books);
            return $this->jsonResponse(
                [
                    "message" => "Updated",
                    "event" => $books->__toArray()
                ],
                200
            );
        }
        if(!isset($data['idBdd'])){
            $books = New BooksEntity();
            $books->setStartAt($data['start']);
            $books->setEndAt($data['end']);
            $books->setIdUser($user->getId());
            $ressource = $this->ressources->find($data['salle'], 'slug');
            $books->ressource_slug = $ressource->getSlug();
            $books->ressource_color = $ressource->getColor();
            $books->ressource_name = $ressource->getName();
            $books->setIdRessource($ressource->getId());
            $this->books->create($books, true);
            $books->setId($this->books->lastInsertId());
            return $this->jsonResponse(
                [
                    "message" => "Created",
                    "event" => $books->__toArray()
                ],
                200
            );
        }
        return $this->jsonResponse(
            $data
        );
    }
}
