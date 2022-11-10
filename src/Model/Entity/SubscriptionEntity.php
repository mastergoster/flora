<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class EventsEntity extends Entity
{
    private $id;
    private $id_users;
    private $started_at;
    private $finished_at;
    private $id_products;
    private $created_at;
    private $updated_at;


    /**
     * Get the value of createdAat
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of createdAat
     *
     * @return  self
     */
    public function setCreatedAt($createdAat)
    {
        $this->created_at = $createdAat;

        return $this;
    }

    /**
     * Get the value of updatedAat
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updatedAat
     *
     * @return  self
     */
    public function setUpdatedAt($updatedAat)
    {
        $this->updated_at = $updatedAat;

        return $this;
    }

    /**
     * Get the value of id_products
     */
    public function getIdProducts()
    {
        return $this->id_products;
    }

    /**
     * Set the value of id_products
     */
    public function setIdProducts($id_products): self
    {
        $this->id_products = $id_products;

        return $this;
    }

    /**
     * Get the value of finished_at
     */
    public function getFinishedAt()
    {
        return $this->finished_at;
    }

    /**
     * Set the value of finished_at
     */
    public function setFinishedAt($finished_at): self
    {
        $this->finished_at = $finished_at;

        return $this;
    }

    /**
     * Get the value of started_at
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * Set the value of started_at
     */
    public function setStartedAt($started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    /**
     * Get the value of id_users
     */
    public function getIdUsers()
    {
        return $this->id_users;
    }

    /**
     * Set the value of id_users
     */
    public function setIdUsers($id_users): self
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
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }
}
