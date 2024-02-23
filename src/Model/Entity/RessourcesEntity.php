<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class RessourcesEntity extends Entity
{
    protected $id;
    protected $name;
    protected $slug;
    protected $description;
    protected $id_images;
    protected $nombre_place;
    protected $created_at;
    protected $updated_at;

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
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


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
    public function getUpdatedAat()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updatedAat
     *
     * @return  self
     */
    public function setUpdatedAat($updatedAat)
    {
        $this->updated_at = $updatedAat;

        return $this;
    }

    /**
     * Get the value of id_images
     */
    public function getIdImages()
    {
        return $this->id_images;
    }

    /**
     * Set the value of id_images
     *
     * @return  self
     */
    public function setIdImages($id_images)
    {
        $this->id_images = $id_images;

        return $this;
    }

    /**
     * Get the value of id_images
     */
    public function getNombrePlace()
    {
        return $this->nombre_place;
    }

    /**
     * Set the value of id_images
     *
     * @return  self
     */
    public function setNombrePlace($nombre_place)
    {
        $this->nombre_place = $nombre_place;

        return $this;
    }
}
