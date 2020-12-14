<?php


namespace Tests\Core\Extension\Twig;

use Twig\TwigFunction;
use PHPUnit\Framework\TestCase;
use Core\Extension\Twig\LinkExtension;

class LinkExtensionTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        parent::setUp();
        \App\App::getInstance()->getRouter()
            ->get('/', 'Shop#index', 'home')
            ->get('/contact', 'Contact#index', 'contact')
            ->get('/test/cool/bien', 'Contact#index', 'bien')
            ->get('/blog/[i:id]-[*:slug]', 'Contact#index', 'blog');
    }


    public function testGetActiveNoParams()
    {
        $linkExtention = new LinkExtension();

        $_SERVER["REQUEST_URI"] = "/";
        $text = $linkExtention->getActive();
        $this->assertEquals(' class="active"', $text);
    }

    public function testGetActiveHomeNoHtml()
    {
        $linkExtention = new LinkExtension();
        $_SERVER["REQUEST_URI"] = "/";
        $text = $linkExtention->getActive('home', false);
        $this->assertEquals(' active', $text);
    }

    public function testGetActiveHomeNoHtmlNiveau3()
    {
        $linkExtention = new LinkExtension();
        $_SERVER["REQUEST_URI"] = "/test/cool/bien";
        $text = $linkExtention->getActive('bien', false, 3);
        $this->assertEquals(' active', $text);
    }

    public function testGetActiveFalseRoute()
    {
        $linkExtention = new LinkExtension();
        $_SERVER["REQUEST_URI"] = "/pierre";
        $text = $linkExtention->getActive('contact', false);
        $this->assertEquals('', $text);
    }


    public function testGetLink()
    {
        $linkExtention = new LinkExtension();
        $this->assertEquals('/test/cool/bien', $linkExtention->getLink('bien'));
    }

    public function testGetLinkWhisParam()
    {
        $linkExtention = new LinkExtension();
        $this->assertEquals(
            '/blog/12-article-de-test',
            $linkExtention->getLink('blog', ['id' => 12, 'slug' => "article-de-test"])
        );
    }

    public function testGetFunctions()
    {
        $actual = (new LinkExtension())->getFunctions();
        $this->assertIsArray($actual);
        $this->assertInstanceOf(TwigFunction::class, $actual[0]);
        $this->assertInstanceOf(TwigFunction::class, $actual[1]);
    }
}
