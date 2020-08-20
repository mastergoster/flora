<?php

namespace App\Model\Entity;

use Core\Model\Entity;
use Core\Controller\Helpers\HController;

class PackagesLogEntity extends Entity
{
    private $id;

    private $id_users;

    private $id_packages;

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
    public function getIdUsers()
    {
        return $this->id_users;
    }

    /**
     * Set the value of id_user
     *
     * @return  self
     */
    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;

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

    /**
     * Get the value of id_packages
     */
    public function getIdPackages()
    {
        return $this->id_packages;
    }

    /**
     * Set the value of id_packages
     *
     * @return  self
     */
    public function setIdPackages($id_packages)
    {
        $this->id_packages = $id_packages;

        return $this;
    }
}
