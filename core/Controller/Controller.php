<?php

namespace Core\Controller;

use Core\Extension\Twig\LinkExtension;
use Core\Controller\SecurityController;
use Core\Extension\Twig\FlashExtension;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{

    private $twig;

    private $app;

    private $messageFlash;

    private $security;

    protected function render(string $view, array $variables = []): Response
    {
        $variables["debugTime"] = $this->getApp()->getDebugTime();
        return  $this->getApp()->response->setContent($this->getTwig()->render(
            $view . '.twig',
            $variables
        ));
    }

    protected function renderPdf(string $view, array $variables = []): Response
    {
        $response = new Response();
        $response->header->set("Content-type", "application/pdf");
        $response->header->set("Content-Disposition", "inline");
        $response->header->set("filename", $variables["title"] ?: "pdf");
        $folder  = $this->getApp()->rootfolder() . "/files/$view/";
        $name = ($variables["title"] ?: "pdf") . ".pdf";

        if (!file_exists($folder . $name)) {
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetTitle($variables["title"] ?: "pdf");
            $mpdf->WriteHTML($this->render($view, $variables));
            $folderLink = "";
            foreach (explode("/", $folder) as $value) {
                if (!is_dir($folderLink . "/" . $value) && $value != "") {
                    \mkdir($folderLink . "/" . $value);
                }
                if ($value != "") {
                    $folderLink = $folderLink . "/" . $value;
                }
            }

            $mpdf->Output($folder . $name, \Mpdf\Output\Destination::FILE);
        }
        $response->header->set("Content-Description", "File Transfer");
        $response->header->set("Content-Type", "application/octet-stream");
        $response->header->set("Content-Disposition", "attachment");
        $response->header->set("filename", basename($folder . $name));
        $response->header->set("Expires", 0);
        $response->header->set("Cache-Control", "must-revalidate");
        $response->header->set("Pragma", "public");
        $response->header->set("Content-Length", filesize($folder . $name));
        return $response->setContent(readfile($folder . $name));
    }

    private function getTwig()
    {
        if (is_null($this->twig)) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(dirname(__DIR__)) . '/views/');
            $this->twig = new \Twig\Environment($loader);
            if ($this->session()->has("users")) {
                $this->security()->userHydrateSession();
            }
            $this->twig->addGlobal('session', $this->session()->all());
            $this->twig->addGlobal('constant', get_defined_constants());
            $this->twig->addExtension(new FlashExtension());
            $this->twig->addExtension(new LinkExtension());
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

    protected function jsonResponse403(string $message = "refusé"): Response
    {
        $response = $this->jsonResponse(["permission" => $message]);
        return $response->setStatusCode(403);
    }

    protected function jsonResponse($data): Response
    {
        $response = new Response;
        $response->setContent(
            json_encode($data)
        );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    protected function getConfig($variable)
    {
        return $this->getApp()->getConfig($variable);
    }

    protected function redirect($path, $params = [])
    {

        if (\substr($path, 0, 1) == "/" || \strpos($path, 0, 4) == "http") {
            $url = $path;
        } else {
            $url = $this->generateUrl($path, $params);
        }
        return new Response('', 200, ["'Location" => $url]);
    }

    public function security()
    {
        if (is_null($this->security)) {
            $this->security = new SecurityController();
        }
        return $this->security;
    }

    public function request()
    {
        return $this->getApp()->request;
    }
    public function session()
    {
        return $this->request()->getSession();
    }
}
