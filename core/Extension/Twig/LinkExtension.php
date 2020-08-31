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



    public function getActive(string $link = "home", bool $html = true, int $count = 1): string
    {
        if ($html) {
            $text = ' class="active"';
        } else {
            $text = ' active';
        }
        for ($i = 1; $i <= $count; $i++) {
            if (explode("/", $this->router->url($link))[$i] != explode("/", $_SERVER["REQUEST_URI"])[$i]) {
                return "";
            }
        }
        return  $text;
    }


    public function getLink(string $path = "home", array $params = []): string
    {
        return $this->router->url($path, $params);
    }
}
