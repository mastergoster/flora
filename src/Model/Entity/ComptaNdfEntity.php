<?php

namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class ComptaNdfEntity extends Entity
{
    private $id;
    private $desc;
    private $debit;
    private $credit;
    private $id_users;
    private $created_at;
    private $updated_at;

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
}
