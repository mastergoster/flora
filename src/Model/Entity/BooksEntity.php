<?php

namespace App\Model\Entity;

use App\App;
use Core\Model\Entity;
use DateTime;

class BooksEntity extends Entity
{

    private $id;
    private $start_at;
    private $end_at;
    private $id_user;
    private $id_ressource;
    private $created_at;
    private $updated_at;


    /**
     * Get the value of start_at
     */
    public function getStartAt()
    {
        return $this->start_at;
    }

    /**
     * Set the value of start_at
     */
    public function setStartAt($start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    /**
     * Get the value of end_at
     */
    public function getEndAt()
    {
        return $this->end_at;
    }

    /**
     * Set the value of end_at
     */
    public function setEndAt($end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    /**
     * Get the value of id_user
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * Set the value of id_user
     */
    public function setIdUser($id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * Get the value of id_ressource
     */
    public function getIdRessource()
    {
        return $this->id_ressource;
    }

    /**
     * Set the value of id_ressource
     */
    public function setIdRessource($id_ressource): self
    {
        $this->id_ressource = $id_ressource;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     */
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    private function editable(): bool
    {
        $user = App::getInstance()->request->getSession()->get('users')->getId() == $this->id_user;
        $futur = (new DateTime($this->start_at))->modify("30 min")->format('Y-m-d H:i:s') > date('Y-m-d H:i:s');
        return  $user && $futur;
        
    }
    private function color(): string
    {
        $color = $this->ressource_color;
        if (!$this->editable()) {
            list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
            return "rgba($r, $g, $b, 0.5)";
        }
        return $this->ressource_color;
    }

    private function titre(): string
    {
        $user = App::getInstance()->request->getSession()->get('users');
        if($user && ($user->getId() == $this->id_user)){
            return "Vous avez réservé le " . $this->ressource_name;
        }
        return $this->ressource_name;
    }

    public function __toArray(): array
    {
        return
        [
            "title"=> $this->titre(),
            "start"=> $this->start_at,
            "end"=> $this->end_at,
            "extendedProps"=> [
                "salle"=> $this->ressource_slug,
                "idBdd"  => $this->id,
                "editable"=> $this->editable(),
            ],
            "textColor"=> "#000000",
            "backgroundColor"=> $this->color(),
            "editable"=> $this->editable()
        ];

    }


}
