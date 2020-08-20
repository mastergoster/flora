<?php

namespace Core\Controller;

class SmsController extends Controller
{
    private $user;
    private $token = "";
    private $url = "";
    private $numero;

    public function __construct()
    {
        $this->loadModel("Users");
        $this->token = $this->getConfig("tokenSms");
        $this->url = $this->getConfig("urlSms");
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
