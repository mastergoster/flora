<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class InvocesEntity extends Entity
{
    private $id;
    private $ref;

    private $id_users;

    private $price;

    private $activate;

    private $date_at;

    private $created_at;

    private $updated_at;

    private $invocesLines;

    /**
     * Get the value of ref
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set the value of ref
     *
     * @return  self
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

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
     *
     * @return  self
     */
    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of activate
     */
    public function getActivate()
    {
        return $this->activate;
    }

    /**
     * Set the value of activate
     *
     * @return  self
     */
    public function setActivate($activate)
    {
        $this->activate = $activate;

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

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of invocesLines
     */
    public function getInvocesLines()
    {
        return $this->invocesLines;
    }

    /**
     * Set the value of invocesLines
     *
     * @return  self
     */
    public function setInvocesLines($invocesLines)
    {
        $this->invocesLines = $invocesLines;

        return $this;
    }

    /**
     * Get the value of date_at
     */
    public function getDateAt()
    {
        return $this->date_at;
    }

    /**
     * Set the value of date_at
     *
     * @return  self
     */
    public function setDateAt($date_at)
    {
        $this->date_at = $date_at;

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
