<?php

namespace Core\Extension\Twig;

use App\App;
use Twig\TwigFunction;
use Core\Controller\FlashController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;

class FlashExtension extends AbstractExtension
{

    /**
     * @var FlashService
     */
    private $flashService;

    public function __construct(SessionInterface $session)
    {
        $this->flashService = new FlashController($session);
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
