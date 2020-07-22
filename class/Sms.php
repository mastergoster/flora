<?php
require_once "class/Adherant.php";
class Sms
{
    private $user;
    private $token = "dcnefilb23454vefvmzroivormhv2345676543";
    private $url = "http://sms.apprendre.co:1880/sms?";
    private $numero;

    public function __construct()
    {
        $this->user = new Adherant();
    }


    public function send($text)
    {
        $url = $this->url . "token=" . $this->token . "&tel=" . $this->numero . "&msg=" . urlencode($text);
        return file_get_contents($url);
    }

    public function numero($numero)
    {
        $reg = "/^0[6-7]([0-9]{2}){4}$/";

        if (preg_match($reg, $numero)) {
            $this->numero = $numero;
        }
        return $this;
    }
}
