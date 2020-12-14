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

    public static function tableObjectToString(string $key, array $array): array
    {
        $method = "get" . ucfirst($key);
        $param = array_map(fn($value) => $value->$method(), $array);
        return $param;
    }
}
