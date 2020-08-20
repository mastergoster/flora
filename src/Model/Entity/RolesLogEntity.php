<?php

namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class RolesLogEntity extends Entity
{
    private $id;

    private $id_roles;

    private $id_users;

    private $created_at;

    /**
     * Get the value of id_role
     */
    public function getIdRoles()
    {
        return $this->id_roles;
    }

    /**
     * Set the value of id_role
     *
     * @return  self
     */
    public function setIdRoles($id_roles)
    {
        $this->id_roles = $id_roles;

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
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
}
