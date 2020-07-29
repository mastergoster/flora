<?php

namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class UserEntity extends Entity
{
    private $id;

    private $nom;

    private $prenom;

    private $mail;

    private $password;

    private $pin;

    private $token;

    private $tel;

    private $activate;

    private $verify;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getVerify()
    {
        return $this->verify;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setMail(string $mail)
    {
        $this->mail = $mail;
    }

    public function setPassword(string $password)
    {
        $password = password_hash(htmlspecialchars($password), PASSWORD_BCRYPT);
        $this->password = $password;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setVerify($verify)
    {
        $this->verify = $verify;
    }

    /**
     * Get the value of nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of pin
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set the value of pin
     *
     * @return  self
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get the value of tel
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set the value of tel
     *
     * @return  self
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

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
}
