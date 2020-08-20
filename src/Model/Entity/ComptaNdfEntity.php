<?php

namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class ComptaNdfEntity extends Entity
{
    private $id;

    private $description;

    private $id_user;

    private $id_ligne;

    private $paye_at;

    private $created_at;




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
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of credit
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set the value of credit
     *
     * @return  self
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get the value of debit
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set the value of debit
     *
     * @return  self
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

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
     * Get the value of id_ligne
     */
    public function getIdLigne()
    {
        return $this->id_ligne;
    }

    /**
     * Set the value of id_ligne
     *
     * @return  self
     */
    public function setIdLigne($id_ligne)
    {
        $this->id_ligne = $id_ligne;

        return $this;
    }

    /**
     * Get the value of paye_at
     */
    public function getPayeAt()
    {
        return $this->paye_at;
    }

    /**
     * Set the value of paye_at
     *
     * @return  self
     */
    public function setPayeAt($paye_at)
    {
        $this->paye_at = $paye_at;

        return $this;
    }
}
