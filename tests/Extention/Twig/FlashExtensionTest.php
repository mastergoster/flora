<?php


namespace Tests\Core\Extension\Twig;

use Twig\TwigFunction;
use Tests\SessionTestCase;
use PHPUnit\Framework\TestCase;
use Core\Controller\FlashController;
use Core\Extension\Twig\FlashExtension;

class FlashExtensionTest extends TestCase
{
    private $session;
    private $flashExtension;

    protected function setUp(): void
    {
        parent::setUp();
        $this->session = new SessionTestCase;
        $this->flashExtension = new FlashExtension($this->session);
    }

    public function testGetFunctions()
    {
        $actual = $this->flashExtension->getFunctions();
        $this->assertIsArray($actual);
        $this->assertInstanceOf(TwigFunction::class, $actual[0]);
    }

    public function testGetFlashTrue()
    {

        $flashController = new FlashController($this->session);
        $flashController->error("pas cool");
        $actual = $this->flashExtension->getFlash("error");
        $this->assertIsArray($actual);
        $this->assertEquals(['pas cool'], $actual);
    }
    public function testGetFlashFalse()
    {
        $actual = $this->flashExtension->getFlash("error");
        $this->assertNull($actual);
    }
}
