<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class GrafikartTest extends TestCase
{
    public function test1egal1 () {
        $this->assertEquals(1, 1);
    }
    
    public function test1egal2 () {
        $this->assertEquals(1, 2);
    }
}