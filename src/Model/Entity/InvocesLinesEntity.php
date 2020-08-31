<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class InvocesLinesEntity extends Entity
{
    private $id;
    private $id_invoces;
    private $id_products;
    private $ref;
    private $name;
    private $desc;
    private $price;
    private $qte;
    private $discount;
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
     * Get the value of id_invoces
     */
    public function getIdInvoces()
    {
        return $this->id_invoces;
    }

    /**
     * Set the value of id_invoces
     *
     * @return  self
     */
    public function setIdInvoces($id_invoces)
    {
        $this->id_invoces = $id_invoces;

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
     *
     * @return  self
     */
    public function setIdProducts($id_products)
    {
        $this->id_products = $id_products;

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
     * Get the value of qte
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set the value of qte
     *
     * @return  self
     */
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * Get the value of discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set the value of discount
     *
     * @return  self
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

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
}
