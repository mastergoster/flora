<?php

namespace App\Controller;

use \Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{
    public function __construct()
    {
    }

    public function index(): Response
    {
        return $this->render('shop/index');
    }
}
