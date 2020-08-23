<?php

namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class ComptaLinesEntity extends Entity
{
    private $id;

    private $description;

    private $credit;

    private $debit;

    private $date;

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
    public function getDateAt()
    {
        return $this->date_at;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDateAt($date)
    {
        $this->date_at = $date;

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
}
