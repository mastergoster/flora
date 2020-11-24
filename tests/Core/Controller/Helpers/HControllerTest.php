<?php

namespace Tests\Core\Controller\Helpers;

use \PHPUnit\Framework\TestCase;
use \Core\Controller\Helpers\HController;

class HControllerTest extends TestCase
{
    // Tests sur "textToDate"
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

    // Tests sur "convertisseurTime"
    public function testConvertisseurTime0s ()
    {
        $Time = 0; // 0 minute et 0 seconde
        $expected = ["h" => 0, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime1s ()
    {
        $Time = 1; // 0 minute et 1 seconde
        $expected = ["h" => 0, "m" => 1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime59s ()
    {
        $Time = 59; // 0 minute et 59 secondes
        $expected = ["h" => 0, "m" => 1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime60s ()
    {
        $Time = 60; // 1 minute et 0 seconde
        $expected = ["h" => 0, "m" => 1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime61s ()
    {
        $Time = 61; // 1 minute et 1 seconde
        $expected = ["h" => 0, "m" => 2];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3540s ()
    {
        $Time = 3540; // 59 minutes et 0 seconde
        $expected = ["h" => 0, "m" => 59];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3541s ()
    {
        $Time = 3541; // 59 minutes et 1 seconde
        $expected = ["h" => 1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3569s ()
    {
        $Time = 3569; // 59 minutes et 29 secondes
        $expected = ["h" => 1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3570s ()
    {
        $Time = 3570; // 59 minutes et 30 secondes
        $expected = ["h" => 1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3599s ()
    {
        $Time = 3599; // 59 minutes et 59 secondes
        $expected = ["h" => 1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3600s ()
    {
        $Time = 3600; // 60 minutes et 0 seconde ou 1 heure 0 minute et 0 seconde
        $expected = ["h" => 1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime3601s ()
    {
        $Time = 3601; // 60 minutes et 1 seconde ou 1 heure 0 minute et 1 seconde
        $expected = ["h" => 1, "m" => 1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime86400s ()
    {
        $Time = 86400; // 24 heure 0 minute et 0 seconde
        $expected = ["h" => 24, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTime100000s ()
    {
        $Time = 100000; // 27 heure 46 minutes et 40 secondes
        $expected = ["h" => 27, "m" => 47];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative0 ()
    {
        $Time = -0;
        $expected = ["h" => 0, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative1s ()
    {
        $Time = -1; // 0 minute et -1 seconde
        $expected = ["h" => 0, "m" => -1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative59s ()
    {
        $Time = -59; // 0 minute et -59 secondes
        $expected = ["h" => 0, "m" => -1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative60s ()
    {
        $Time = -60; // -1 minute et 0 seconde
        $expected = ["h" => 0, "m" => -1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative61s ()
    {
        $Time = -61; // -1 minute et -1 seconde
        $expected = ["h" => 0, "m" => -2];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3540s ()
    {
        $Time = -3540; // -59 minutes et 0 seconde
        $expected = ["h" => 0, "m" => -59];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3541s ()
    {
        $Time = -3541; // -59 minutes et -1 seconde
        $expected = ["h" => -1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3569s ()
    {
        $Time = -3569; // -59 minutes et -29 secondes
        $expected = ["h" => -1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3570s ()
    {
        $Time = -3570; // -59 minutes et -30 secondes
        $expected = ["h" => -1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3599s ()
    {
        $Time = -3599; // -59 minutes et -59 secondes
        $expected = ["h" => -1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3600s ()
    {
        $Time = -3600; // -60 minutes et 0 seconde ou -1 heure 0 minute et 0 seconde
        $expected = ["h" => -1, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative3601s ()
    {
        $Time = -3601; // -60 minutes et -1 seconde ou -1 heure 0 minute et -1 seconde
        $expected = ["h" => -1, "m" => -1];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative86400s ()
    {
        $Time = -86400; // -24 heure 0 minute et 0 seconde
        $expected = ["h" => -24, "m" => 0];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    public function testConvertisseurTimeNegative100000s ()
    {
        $Time = -100000; // -27 heure -46 minutes et -40 secondes
        $expected = ["h" => -27, "m" => -47];

        $this->assertEquals($expected, HController::convertisseurTime($Time));
    }

    // public function testConvertisseurTime ()
    // {
    //     $Time = -86400;
    //     $expected = HController::convertisseurTime($Time);

    //     dump($expected);
    // }
}
