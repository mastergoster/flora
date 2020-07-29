<?php


namespace Core\Controller;

class FlashController
{

    private $sessionKey = "flash";

    private $message;


    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function success(string $message)
    {
        $flash = $this->sessionGet($this->sessionKey);
        $flash['success'][] = $message;
        $this->sessionSet($this->sessionKey, $flash);
    }

    public function error(string $message)
    {
        $flash = $this->sessionGet($this->sessionKey);
        $flash['error'][] = $message;
        $this->sessionSet($this->sessionKey, $flash);
    }

    /**
     * @param string $type
     * @return null|string
     */
    public function get(string $type): ?array
    {
        if (is_null($this->message)) {
            $this->message = $this->sessionGet($this->sessionKey, []);
            $this->sessionDelete($this->sessionKey);
        }
        if (array_key_exists($type, $this->message)) {
            return $this->message[$type];
        }
        return null;
    }


    /**
     * Récupere une info en session
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function sessionGet(string $key, $default = null)
    {

        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * Ajoute un info en session
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    private function sessionSet(string $key, $value): void
    {

        $_SESSION[$key] = $value;
    }

    /**
     * Supprime une clé de la session
     *
     * @param string $key
     * @return mixed
     */
    private function sessionDelete(string $key): void
    {

        unset($_SESSION[$key]);
    }
}
