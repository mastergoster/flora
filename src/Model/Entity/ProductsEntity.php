<?php

namespace App\Model\Entity;

use Core\Model\Entity;
use Core\Controller\Helpers\HController;
use Exception;

class ProductsEntity extends Entity
{
    private $id;
    private $ref;
    private $name;
    private $desc;
    private $price;
    private $activate;
    private $subscribeable;
    private $duration;
    private $unity;
    private $created_at;
    private $updated_at;

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
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of desc
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set the value of desc
     *
     * @return  self
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

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
     * Get the value of subscribeable
     */
    public function getSubscribeable()
    {
        return $this->subscribeable;
    }

    /**
     * Set the value of subscribeable
     */
    public function setSubscribeable($subscribeable): self
    {
        $this->subscribeable = $subscribeable;

        return $this;
    }

    /**
     * Get the value of duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     */
    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get the value of unity
     */
    public function getUnity()
    {
        return $this->unity;
    }

    /**
     * Set the value of unity
     */
    public function setUnity($unity): self
    {
        if (!in_array($unity, ["month", "day", "hour"])) {
            throw new Exception("this format is invalid", 500);
        }
        $this->unity = $unity;
        return $this;
    }
}
