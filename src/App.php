<?php

namespace App;

use \Core\Controller\URLController;
use \Core\Controller\RouterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Core\Controller\Database\DatabaseController;
use \Core\Controller\Database\DatabaseMysqliteController;
use Symfony\Component\HttpFoundation\Session\Session;

class App
{

    private static $INSTANCE;

    public $title;

    private $router;
    private $startTime;
    private $db_instance;
    private $config;


    public static function getInstance()
    {
        if (is_null(self::$INSTANCE)) {
            self::$INSTANCE = new App();
        }
        return self::$INSTANCE;
    }

    public static function load()
    {
        $install = true;
        $app = self::getInstance();
        $app->request = Request::createFromGlobals();
        $app->request->setSession(new Session());
        if (session_status() !== PHP_SESSION_ACTIVE) {
            //$app->request->getSession()->start();
        }
        $app->request->hasPreviousSession();
        $session = $_SESSION ?: [];
        //retrocomptatibility
        unset($session["_sf2_attributes"]);
        unset($session["_symfony_flashes"]);
        unset($session["_sf2_meta"]);
        foreach ($session as $key => $value) {
            $app->request->getSession()->set($key, $value);
        }
        setlocale(\LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1', 'fr_FR.utf8');

        date_default_timezone_set('Europe/Paris');
        $app->response = new Response();
        $config = $app->rootfolder();
        if (file_exists($config . "/.env")) {
            $dotenv = \Dotenv\Dotenv::createImmutable($config, "/.env");
            $dotenv->load();
            $install = false;
        }
        if (file_exists($config . "/config.php")) {
            foreach ($app->getConfig() as $key => $value) {
                if (!\array_key_exists($key, $_ENV)) {
                    putenv("$key=$value");
                }
            }
            $install = false;
        }
        if ($install) {
            var_dump("instalation obligatoire config.php ou .env");
            die();
        }

        if (getenv("ENV_DEV")) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }


        $numPage = URLController::getPositiveInt('page');

        if ($numPage !== null) {
            // url /categories?page=1&parm2=pomme
            if ($numPage == 1) {
                $uri = explode('?', $_SERVER["REQUEST_URI"])[0];
                $get = $_GET;
                unset($get["page"]);
                $query = http_build_query($get);
                if (!empty($query)) {
                    $uri = $uri . '?' . $query;
                }
                http_response_code(301);
                header('location: ' . $uri);
                exit();
            }
        }
    }

    public function getRouter($basePath = "/var/www/"): RouterController
    {
        if (is_null($this->router)) {
            $this->router = new RouterController($basePath . 'views');
        }
        return $this->router;
    }

    public function setStartTime()
    {
        $this->startTime = microtime(true);
    }

    public function getDebugTime()
    {
        return number_format((microtime(true) - $this->startTime) *  1000, 2);
    }

    public function getTable(string $nameTable)
    {
        $nameTable = "\\App\\Model\\Table\\" . ucfirst($nameTable) . "Table";
        return new $nameTable($this->getDb());
    }

    public function getDb(): DatabaseController
    {
        if (is_null($this->db_instance)) {
            $name = getenv('DB_Name');
            if (getenv("ENV_DEV")) {
                $name .= ".dev";
            } elseif (getenv("ENV_DEV") == 'test') {
                $name .= ".test";
            } else {
                $name .= ".prod";
            }
            $this->db_instance = new DatabaseMysqliteController(
                $name,
                getenv('DB_User'),
                getenv('DB_Password'),
                getenv('DB_Url')
            );
        }
        return $this->db_instance;
    }
    public static function rootfolder()
    {
        return dirname(dirname(__FILE__));
    }

    public function getConfig(?string $var = null)
    {

        if (is_null($this->config)) {
            $this->config = [];
            if (file_exists($this->rootfolder() . "/config.php")) {
                $this->config = require_once $this->rootfolder() . "/config.php";
            }
        }
        if ($var === null) {
            return $this->config;
        }
        if (\array_key_exists($var, $_ENV)) {
            return  getenv($var);
        }
        if (\array_key_exists($var, $this->config)) {
            return $this->config[$var];
        }
        return false;
    }
}
