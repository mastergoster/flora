<?php

namespace Core\Controller;

use mysql_xdevapi\Exception;

class RouterController
{

    private $router;

    private $viewPath;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
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

        if (!is_array($match) && !strpos($match['target'], "#")) {
            $controller = "App\\Controller\\ErrorsController";
            $method = "er404";
            $match['params'] = [];
        } else {
            [$controller, $method] = explode("#", $match['target']);
            $controller = "App\\Controller\\" . ucfirst($controller) . "Controller";
        }

        echo (new $controller())->$method(...array_values($match['params']));
    }
}
