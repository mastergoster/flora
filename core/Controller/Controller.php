<?php

namespace Core\Controller;

use App\App;
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
        $folder  = $this->getApp()->rootfolder() . "/files/$view/";
        $title = (isset($variables["title"]) ? $variables["title"] : "pdf");
        $name =  $title . ".pdf";

        if (!file_exists($folder . $name)) {
            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 20,
                'margin_right' => 15,
                'margin_top' => 48,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);
            $mpdf->SetTitle($title);

            $mpdf->SetAuthor("CoWorkInMoulins by FLORA");
            if (isset($paid)) {
                $mpdf->SetWatermarkText("Paid");
                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
            }

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML($this->render($view, $variables)->getContent());
            $folderLink = "";
            foreach (explode("/", $folder) as $value) {
                if (!is_dir($folderLink . "/" . $value) && $value != "") {
                    \mkdir($folderLink . "/" . $value);
                }
                if ($value != "") {
                    $folderLink = $folderLink . "/" . $value;
                }
            }
            $mpdf->setProtection([]);
            $mpdf->Output($folder . $name, \Mpdf\Output\Destination::FILE);
        }
        $response->headers->set("Content-Description", "File Transfer");
        $response->headers->set("Content-Type", "application/octet-stream");
        $response->headers->set("Content-Disposition", "attachment; filename=\"" . basename($folder . $name) . '"');
        $response->headers->set("Expires", 0);
        $response->headers->set("Cache-Control", "must-revalidate");
        $response->headers->set("Pragma", "public");
        $response->headers->set("Content-Length", filesize($folder . $name));
        \ob_start();
        readfile($folder . $name);
        return $response->setContent(\ob_get_clean());
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
            $this->twig->addExtension(new FlashExtension(App::getInstance()->request->getSession()));
            $this->twig->addExtension(new LinkExtension(App::getInstance()->getRouter()));
        }
        return $this->twig;
    }

    protected function getApp(): \App\App
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
            $this->messageFlash = new FlashController(App::getInstance()->request->getSession());
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

        if (\substr($path, 0, 1) == "/" || \substr($path, 0, 4) == "http") {
            $url = $path;
        } else {
            $url = $this->generateUrl($path, $params);
        }
        $response = new Response('', 303);
        $response->headers->set("Location", $url);
        //dd($response);
        return $response;
    }

    public function security()
    {
        if (is_null($this->security)) {
            $this->security = new SecurityController($this->getApp()->getDb(), $this->session());
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
