<?php


namespace Core\Extension\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LinkExtension extends AbstractExtension
{


    public function __construct()
    {
        $this->router = \App\App::getInstance()->getRouter();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('linkactive', [$this, 'getActive'], [
                'is_safe' => ['html']
            ]),
            new TwigFunction('path', [$this, 'getLink'])
        ];
    }



    public function getActive(string $link = "home"): string
    {

        if (explode("/", $this->router->url($link))[1] == explode("/", $_SERVER["REQUEST_URI"])[1]) {
            return ' class="active"';
        }
        return "";
    }


    public function getLink(string $path = "home", array $params = []): string
    {
        return $this->router->url($path, $params);
    }
}
