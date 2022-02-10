<?php

namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class UsersEntity extends Entity
{
    /**
     * Undocumented variable
     *
     * @var int
     */
    private $id;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $password;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $first_name;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $last_name;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $pin;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $token;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $phone_number;

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    private $activate;

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    private $verify;

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

    private $email;

    private $postal_code;

    private $id_images;

    private $street;

    private $city;

    private $desc;

    private $display;


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
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $password  Undocumented variable
     *
     * @return  self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $first_name  Undocumented variable
     *
     * @return  self
     */
    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $last_name  Undocumented variable
     *
     * @return  self
     */
    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $pin  Undocumented variable
     *
     * @return  self
     */
    public function setPin(string $pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $token  Undocumented variable
     *
     * @return  self
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $phone_number  Undocumented variable
     *
     * @return  self
     */
    public function setPhoneNumber(string $phone_number)
    {
        $this->phone_number = $phone_number;

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
     * @return  boolean
     */
    public function getVerify()
    {

        return $this->verify  == "0" ? false : true;
    }

    /**
     * Set undocumented variable
     *
     * @param  boolean  $verify  Undocumented variable
     *
     * @return  self
     */
    public function setVerify(bool $verify)
    {
        $this->verify = $verify;

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
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of postal_code
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set the value of postal_code
     *
     * @return  self
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;

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
     * Get the value of display
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set the value of display
     *
     * @return  self
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get the value of street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     *
     * @return  self
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get the value of city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */
    public function setCity($city)
    {
        $this->city = $city;

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
