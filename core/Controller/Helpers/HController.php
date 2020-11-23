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
        // Vérifie si les heures sont présentes, sinon les rajoutes
        if (strlen($text) == 10)
        {
            $text = $text . " 00:00:00";
        }

        // Vérifie s'il y a des "/", si oui, les changes en "-"
            $text = str_replace("/", "-", $text);

        // Vérifie si le format est EU, si oui, recompose en format US
        if (strpos($text, "-") == 2)
        {
            $text_Hour = explode(" ", $text);
            $text_Hour[0] = implode("-", array_reverse(explode("-", $text_Hour[0])));
            $text = implode(" ", $text_Hour);
        }
        
        return \DateTime::createFromFormat('Y-m-d H:i:s', $text);
    }
}
