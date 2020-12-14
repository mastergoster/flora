<?php


namespace Tests\Core\Extension\Twig;

use Twig\TwigFunction;
use PHPUnit\Framework\TestCase;
use Core\Controller\RouterController;
use Core\Extension\Twig\LinkExtension;

class LinkExtensionTest extends TestCase
{

    private $linkExtention;
    protected function setUp(): void
    {
        $router = new RouterController("/var/www/views");
        $router->get('/', 'Shop#index', 'home')
            ->get('/contact', 'Contact#index', 'contact')
            ->get('/test/cool/bien', 'Contact#index', 'bien')
            ->get('/blog/[i:id]-[*:slug]', 'Contact#index', 'blog');

        $this->linkExtention = new LinkExtension($router);
    }


    public function testGetActiveNoParams()
    {

        $_SERVER["REQUEST_URI"] = "/";
        $text = $this->linkExtention->getActive();
        $this->assertEquals(' class="active"', $text);
    }

    public function testGetActiveHomeNoHtml()
    {
        $_SERVER["REQUEST_URI"] = "/";
        $text = $this->linkExtention->getActive('home', false);
        $this->assertEquals(' active', $text);
    }

    public function testGetActiveHomeNoHtmlNiveau3()
    {

        $_SERVER["REQUEST_URI"] = "/test/cool/bien";
        $text = $this->linkExtention->getActive('bien', false, 3);
        $this->assertEquals(' active', $text);
    }

    public function testGetActiveFalseRoute()
    {

        $_SERVER["REQUEST_URI"] = "/pierre";
        $text = $this->linkExtention->getActive('contact', false);
        $this->assertEquals('', $text);
    }


    public function testGetLink()
    {
        $this->assertEquals('/test/cool/bien', $this->linkExtention->getLink('bien'));
    }

    public function testGetLinkWhisParam()
    {
        $this->assertEquals(
            '/blog/12-article-de-test',
            $this->linkExtention->getLink('blog', ['id' => 12, 'slug' => "article-de-test"])
        );
    }

    public function testGetFunctions()
    {
        $actual = $this->linkExtention->getFunctions();
        $this->assertIsArray($actual);
        $this->assertInstanceOf(TwigFunction::class, $actual[0]);
        $this->assertInstanceOf(TwigFunction::class, $actual[1]);
    }
}
