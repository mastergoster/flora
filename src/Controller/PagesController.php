<?php

namespace App\Controller;

use \Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends Controller
{
    public function __construct()
    {
    }

    public function index(): Response
    {
        return $this->render('pages/index', []);
    }

    public function legal(): Response
    {
        return $this->render('pages/legal');
    }
}
