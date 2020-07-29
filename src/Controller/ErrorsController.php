<?php

namespace App\Controller;

use \Core\Controller\Controller;

class ErrorsController extends Controller
{

    public function er404(?array $param = null)
    {
        return $this->render(
            'layout/default',
            [
                "title" => "404",
            ]
        );
    }
}
