<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class ImagesEntity extends Entity
{
    /**
     * Undocumented variable
     *
     * @var int
     */
    private $id;

    private $ref;
    private $name;
    private $desc;


    /**
     * Undocumented variable
     *
     * @var boolean
     */
    private $activate;


    /**
     * Undocumented variable
     *
     * @var \Datetime
     */
    private $created_at;

    /**
     * Undocumented variable
     *
     * @var \Datetime
     */
    private $updated_at;



    /**
     * Get undocumented variable
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set undocumented variable
     *
     * @param  int  $id  Undocumented variable
     *
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Get undocumented variable
     *
     * @return  boolean
     */
    public function getActivate()
    {
        return $this->activate == "0" ? false : true;
    }

    /**
     * Set undocumented variable
     *
     * @param  boolean  $activate  Undocumented variable
     *
     * @return  self
     */
    public function setActivate(bool $activate)
    {
        $this->activate = $activate;

        return $this;
    }


    /**
     * Get undocumented variable
     *
     * @return  \Datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set undocumented variable
     *
     * @param  \Datetime  $created_at  Undocumented variable
     *
     * @return  self
     */
    public function setCreatedAt(\Datetime $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set undocumented variable
     *
     * @param  \Datetime  $updated_at  Undocumented variable
     *
     * @return  self
     */
    public function setUpdatedAt(\Datetime $updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

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
}
