<?php

namespace App\Model\Entity;

use Core\Model\Entity;

class InvocesEntity extends Entity
{
    private $id;
    private $ref;

    private $id_users;

    private $user;

    private $price;

    private $paiement;

    private $activate;

    private $date_at;

    private $created_at;

    private $updated_at;

    private $invocesLines;

    private $ref_stripe_token;

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
        if ($this->price === null && $this->invocesLines !== null) {
            $price = 0;
            foreach ($this->invocesLines as $line) {
                $price += $line->getPrice() * $line->getQte();
            }
            return "{ $price }";
        }
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

    /**
     * Get the value of refStripeToken
     */
    public function getRefStripeToken()
    {
        return $this->ref_stripe_token;
    }

    /**
     * Set the value of refStripeToken
     *
     * @return  self
     */
    public function setRefStripeToken($refStripeToken)
    {
        $this->ref_stripe_token = $refStripeToken;

        return $this;
    }

    /**
     * Get the value of paiement
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * set the value of paiement
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

        return $this;
    }



    /**
     * recupère l'état du payment
     *
     * @return  self
     */
    public function statePaiement()
    {
        if ($this->price === null) {
            return false;
        }

        if ($this->getPaiement()) {
            $pay = 0;
            foreach ($this->getPaiement() as $line) {
                /** @var ComptaLinesEntity $line */
                $pay += $line->getCredit() - $line->getDebit();
            }
            return $this->price - $pay;
        }
        return $this->price;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
