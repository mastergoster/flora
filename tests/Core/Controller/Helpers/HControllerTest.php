<?php

namespace Tests\Core\Controller\Helpers;

use \PHPUnit\Framework\TestCase;
use \Core\Controller\Helpers\HController;

class HControllerTest extends TestCase
{
    public function testTextToDate()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-03-24 20:35:19");
        $text = "2020-03-24 20:35:19";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithoutHour()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2099-12-25 00:00:00");
        $text = "2099-12-25";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithFrenchFormat()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "1980-07-14 12:26:32");
        $text = "14-07-1980 12:26:32";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithFrenchFormatWithoutHour()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2016-11-01 00:00:00");
        $text = "01-11-2016";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithSlash()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-11-23 16:05:25");
        $text = "2020/11/23 16:05:25";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithSlashWithoutHour()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-11-23 00:00:00");
        $text = "2020/11/23";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithFrenchFormatWithSlashW()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-11-23 16:07:23");
        $text = "23/11/2020 16:07:23";

        $this->assertEquals($expected, HController::textToDate($text));
    }

    public function testTextToDateWithFrenchFormatWithSlashWithoutHour()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-11-23 00:00:00");
        $text = "23/11/2020";

        $this->assertEquals($expected, HController::textToDate($text));
    }
}
