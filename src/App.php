<?php

namespace App;

use \Core\Controller\RouterController;
use \Core\Controller\URLController;
use \Core\Controller\Database\DatabaseMysqliteController;
use \Core\Controller\Database\DatabaseController;

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
        //if (getenv("ENV_DEV")) {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        //}
        define('TVA', 1.2);
        session_start();
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

    public function getRouter($basePath = "/var/www"): RouterController
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
            $this->db_instance = new DatabaseMysqliteController(
                "application",
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD'),
                getenv('CONTAINER_MYSQL')
            );
        }
        return $this->db_instance;
    }
    public static function rootfolder()
    {
        return dirname(dirname(__FILE__));
    }

    public function getConfig(string $var)
    {
        if (is_null($this->config)) {
            $this->config = require_once $this->rootfolder() . "/config.php";
        }
        if (\array_key_exists($var, $this->config)) {
            return $this->config[$var];
        }
        return false;
    }
}
