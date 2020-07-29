<?php

namespace Core\Controller;

use Core\Extension\Twig\FlashExtension;

abstract class Controller
{

    private $twig;

    private $app;

    private $messageFlash;

    protected function render(string $view, array $variables = [])
    {

        $variables["debugTime"] = $this->getApp()->getDebugTime();
        return $this->getTwig()->render(
            $view . '.twig',
            $variables
        );
    }

    private function getTwig()
    {
        if (is_null($this->twig)) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(dirname(__dir__)) . '/views/');
            $this->twig = new \Twig\Environment($loader);
            $this->twig->addGlobal('session', $_SESSION);
            $this->twig->addGlobal('constant', get_defined_constants());
            $this->twig->addExtension(new FlashExtension());
        }
        return $this->twig;
    }

    protected function getApp()
    {
        if (is_null($this->app)) {
            $this->app = \App\App::getInstance();
        }
        return $this->app;
    }

    protected function generateUrl(string $routeName, array $params = []): String
    {
        return $this->getApp()->getRouter()->url($routeName, $params);
    }

    protected function loadModel(string $nameTable): void
    {
        $this->$nameTable = $this->getApp()->getTable($nameTable);
    }

    protected function messageFlash()
    {
        if (is_null($this->messageFlash)) {
            $this->messageFlash = new FlashController();
        }
        return $this->messageFlash;
    }

    protected function jsonResponse403($message = "refusé")
    {
        header('HTTP/1.0 403 Forbidden');
        header('Content-Type: application/json');
        echo json_encode(["permission" => $message]);
        exit();
    }

    protected function getConfig($variable)
    {
        return $this->getApp()->getConfig($variable);
    }
}
