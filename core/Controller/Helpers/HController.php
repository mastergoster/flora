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
                $minutes = floor($Time / 60);
            }

            $secondes = floor($Time % 60);
        } else {
            // Calcule le nombre d'heure complète dans $Time
            $heures = floor($Time / 3600);
            // Calcule le nombre de minutes complète dans $Time après soustraction des heures complètes
            $minutes = floor(($Time - ($heures * 3600)) / 60);
            // Calcule le nombre de secondes complète dans $Time après soustraction des heures et minutes complètes
            $secondes = $Time - ($heures * 3600) - ($minutes * 60);
        }

        if ($secondes > 0) {
            $minutes += 1;
        }

        if ($minutes >= 60) {
            $heures += 1;
            $minutes = $minutes - 60;
        }

        if ($neg) {
            $heures = -$heures;
        }
        return ["h" => (int)$heures, "m" => (int)$minutes];
    }

    public static function textToDate(string $text)
    {
        // Vérifie si les heures sont présentes, sinon les rajoutes
        if (strlen($text) == 10) {
            $text = $text . " 00:00:00";
        }

        // Vérifie s'il y a des "/", si oui, les changes en "-"
            $text = str_replace("/", "-", $text);

        // Vérifie si le format est EU, si oui, recompose en format US
        if (strpos($text, "-") == 2) {
            $text_Hour = explode(" ", $text);
            $text_Hour[0] = implode("-", array_reverse(explode("-", $text_Hour[0])));
            $text = implode(" ", $text_Hour);
        }
        
        return \DateTime::createFromFormat('Y-m-d H:i:s', $text);
    }
}
