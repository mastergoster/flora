<?php


namespace Core\Extension\Twig;

use Twig\TwigFunction;
use Core\Controller\RouterController;
use Twig\Extension\AbstractExtension;

class LinkExtension extends AbstractExtension
{

    private $router;

    public function __construct(RouterController $router)
    {
        $this->router = $router;
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


    public function getLink(string $path = "home", array $params = [], bool $full = false): string
    {
        $url = $full ? "http://" . $_SERVER["HTTP_HOST"] : '';
        return $url . $this->router->url($path, $params);
    }
}
