<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class EventsEntity extends Entity
{
    private $id;
    private $title;
    private $slug;
    private $description;
    private $cover;
    private $date_at;
    private $publish;
    private $created_at;
    private $updated_at;

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
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Get the value of cover
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set the value of cover
     *
     * @return  self
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get the value of dateAt
     */
    public function getDateAt()
    {
        return $this->date_at;
    }

    /**
     * Set the value of dateAt
     *
     * @return  self
     */
    public function setDateAt($dateAt)
    {
        $this->date_at = $dateAt;

        return $this;
    }

    /**
     * Get the value of publish
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set the value of publish
     *
     * @return  self
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get the value of createdAat
     */
    public function getCreatedAat()
    {
        return $this->created_at;
    }

    /**
     * Set the value of createdAat
     *
     * @return  self
     */
    public function setCreatedAat($createdAat)
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
}
