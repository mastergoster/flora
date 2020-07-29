<?php
namespace Tests\Core\Model;

use \PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public function testExtractTableName()
    {
        $table = new ClassTest\MotMotMotusTable(new \Core\Controller\Database\DatabaseController());
        $this->assertEquals(
            "mot_mot_motus",
            $table->extractTableName()
        );
    }

    public function testExtractTableName2()
    {
        $table = new ClassTest\ClassNameTable(new \Core\Controller\Database\DatabaseController());
        $this->assertEquals(
            "class_name",
            $table->extractTableName()
        );
    }
}
