<?php


namespace Core\Extension\Twig;

use Core\Controller\FlashController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashExtension extends AbstractExtension
{

    /**
     * @var FlashService
     */
    private $flashService;

    public function __construct()
    {
        $this->flashService = new FlashController();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getFlash'])
        ];
    }



    public function getFlash(string $type): ?array
    {
        return $this->flashService->get($type);
    }
}
