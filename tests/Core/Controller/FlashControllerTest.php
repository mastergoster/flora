<?php

namespace Tests\Core\Controller;

use Core\Controller\FlashController;
use \PHPUnit\Framework\TestCase;
use Tests\SessionTestCase;

class FlashControllerTest extends TestCase
{

    private $session;
    protected function setUp(): void
    {
        parent::setUp();
        $this->session = new SessionTestCase;
    }

    public function testSuccess()
    {
        $flashController = new FlashController($this->session);
        $flashController->success("cool");
        $this->assertTrue($this->session->has("flash"));
        $this->assertEquals(["success" => ["cool"]], $this->session->get("flash"));
    }
    public function testError()
    {
        $flashController = new FlashController($this->session);
        $flashController->error("pas cool");
        $this->assertTrue($this->session->has("flash"));
        $this->assertEquals(["error" => ["pas cool"]], $this->session->get("flash"));
    }

    public function testGetExist()
    {
        $flashController = new FlashController($this->session);
        $flashController->error("pas cool");
        $this->assertTrue($this->session->has("flash"));
        $this->assertEquals(["pas cool"], $flashController->get("error"));
        $this->assertFalse($this->session->has("flash"));
    }

    public function testGetNotExist()
    {
        $flashController = new FlashController($this->session);
        $this->assertFalse($this->session->has("flash"));
        $this->assertEquals(null, $flashController->get("error"));
    }
}
