<?php

namespace App\Controller;

use \Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ErrorsController extends Controller
{

    public function er404(?array $param = null): Response
    {
        return $this->render(
            'layout/default',
            [
                "title" => "404",
            ]
        );
    }
}
