<?php

namespace Core\Controller\Helpers;

class HController
{
    public static function convertisseurTime(string $Time): array
    {

        $neg = false;
        if ($Time < 0) {
            $neg = true;
            $Time = abs($Time);
        }
        if ($Time < 3600) {
            $heures = 0;

            if ($Time < 60) {
                $minutes = 0;
            } else {
                $minutes = round($Time / 60);
            }

            $secondes = floor($Time % 60);
        } else {
            $heures = round($Time / 3600);
            $secondes = round($Time % 3600);
            $minutes = floor($secondes / 60);
        }

        $secondes2 = round($secondes % 60);
        if ($secondes2 > 0) {
            $minutes += 1;
        }
        if ($neg) {
            $heures = -$heures;
            $minutes = $minutes;
        }
        return ["h" => (int)$heures, "m" => (int)$minutes];
    }

    public static function textToDate(string $text)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $text);
    }
}
