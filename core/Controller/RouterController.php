<?php

namespace Core\Controller;

use App\RouteConfig;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Response;

class RouterController
{

    private $router;

    private $viewPath;

    public function __construct(string $viewPath, ?RouteConfig $routeConfig = null)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
        $this->addConfig($routeConfig);
        $this->addConfig(new \Core\RouteConfig());
    }

    public function addConfig($routeConfig = null): self
    {
        if (!is_null($routeConfig)) {
            foreach ($routeConfig->getConfig() as $route) {
                $methode = $route[0];
                $this->$methode($route[1], $route[2], $route[3]);
            };
        }
        return $this;
    }

    public function get(string $uri, string $file, string $name): self
    {
        $this->router->map('GET', $uri, $file, $name);
        return $this;
    }

    public function post(string $uri, string $file, string $name): self
    {
        $this->router->map('POST', $uri, $file, $name);
        return $this;
    }

    public function match(string $uri, string $file, string $name): self
    {
        $this->router->map('GET|POST', $uri, $file, $name);
        return $this;
    }

    public function url(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

    public function run(): void
    {

        $match = $this->router->match();

        $folder = "";

        if (!is_array($match) || !strpos($match['target'], "#")) {
            $controller = "Core\\Controller\\ErrorsController";
            $method = "er404";
            $match['params'] = [];
        } else {
            [$controller, $method] = explode("#", $match['target']);
            if (substr($controller, 0, 5) == "Admin") {
                $folder = "Admin\\";
            } elseif (substr($controller, 0, 3) == "Ges") {
                $folder = "Gestion\\";
            }

            if (
                class_exists("App\\Controller\\" . $folder . ucfirst($controller) . "Controller") &&
                method_exists("App\\Controller\\" . $folder . ucfirst($controller) . "Controller", $method)
            ) {
                $controller = "App\\Controller\\" . $folder . ucfirst($controller) . "Controller";
            } elseif (
                class_exists("Core\\Controller\\" . $folder . ucfirst($controller) . "Controller") &&
                method_exists("Core\\Controller\\" . $folder . ucfirst($controller) . "Controller", $method)
            ) {
                $controller = "Core\\Controller\\" . $folder . ucfirst($controller) . "Controller";
            } else {
                if (!getenv("ENV_DEV")) {
                    $controller = "Core\\Controller\\ErrorsController";
                    $method = "er404";
                    $match['params'] = [];
                }
            }
        }

        $response = (new $controller())->$method(...array_values($match['params']));
        if ($response instanceof Response) {
            $response->send();
        } else {
            //echo $response;
        }
    }
}
