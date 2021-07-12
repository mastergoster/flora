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
    }

    public function addConfig(?RouteConfig $routeConfig = null): self
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

        $admin = "";

        if (!is_array($match) && !strpos($match['target'], "#")) {
            $controller = "App\\Controller\\ErrorsController";
            $method = "er404";
            $match['params'] = [];
        } else {
            [$controller, $method] = explode("#", $match['target']);
            if (substr($controller, 0, 5) == "Admin") {
                $admin = "Admin\\";
            }
            $controller = "App\\Controller\\" . $admin . ucfirst($controller) . "Controller";
        }

        $response = (new $controller())->$method(...array_values($match['params']));
        if ($response instanceof Response) {
            $response->send();
        } else {
            //echo $response;
        }
    }
}
