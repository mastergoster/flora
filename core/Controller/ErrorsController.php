<?php

namespace Core\Controller;

use \Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ErrorsController extends Controller
{

    public function er404(?array $param = null): Response
    {
        return $this->render(
            'errors/404',
            [
                "title" => "404 il n'y a rien ici ",
            ]
        );
    }
}
