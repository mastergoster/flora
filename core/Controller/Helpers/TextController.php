<?php

namespace Core\Controller\Helpers;

class TextController
{
    public static function excerpt(string $content, int $limit = 100): string
    {
        $text = strip_tags($content);
        if (strlen($text) <= $limit) {
            return $text;
        }
        //return substr($text, 0, (strpos($text, ' ', $limit-1)?: $limit)). "...";

        if (strpos($text, ' ', $limit - 1)) {
            $lastpos = strpos($text, ' ', $limit - 1);
        } else {
            $lastpos = $limit;
        }
        //$lastpos = strpos($text, ' ', $limit-1)?: $limit;

        return substr($text, 0, $lastpos) . "...";
    }
}
