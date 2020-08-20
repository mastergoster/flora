<?php

namespace Core\Controller\Helpers;

class TableauController
{
    public static function assocId(array $array, $key = "id"): array
    {
        $method = "get" . ucfirst($key);
        $new = [];
        foreach ($array as $value) {
            $new[$value->$method()] = $value;
        }
        return $new;
    }
}
