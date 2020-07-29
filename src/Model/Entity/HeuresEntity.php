<?php

namespace App\Model\Entity;

use Core\Model\Entity;
use Core\Controller\Helpers\HController;

class HeuresEntity extends Entity
{
    private $id;

    private $id_user;

    private $created_at;

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return HController::textToDate($this->created_at);
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
