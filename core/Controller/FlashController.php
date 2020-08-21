<?php


namespace Core\Controller;

use App\App;

class FlashController
{

    private $sessionKey = "flash";

    private $message;

    private $session;


    public function __construct()
    {
        $this->session = App::getInstance()->request->getSession();
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

        if ($this->session->has($key)) {
            return $this->session->get($key);
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
        $this->session->set($key, $value);
    }

    /**
     * Supprime une clé de la session
     *
     * @param string $key
     * @return mixed
     */
    private function sessionDelete(string $key): void
    {
        $this->session->remove($key);
    }
}
