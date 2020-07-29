<?php

namespace App\Model\Entity;

use Core\Model\Entity;
use Core\Controller\Helpers\HController;

class ForfaitLogEntity extends Entity
{
    private $id;

    private $id_user;

    private $id_forfait;

    private $created_at;

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return HController::textToDate($this->created_at . " 12:00:00");
    }


    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of id_forfait
     */
    public function getIdForfait()
    {
        return $this->id_forfait;
    }

    /**
     * Set the value of id_forfait
     *
     * @return  self
     */
    public function setIdForfait($id_forfait)
    {
        $this->id_forfait = $id_forfait;

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
     *
     * @return  self
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;

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
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
