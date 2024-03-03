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
        $nobot = time() . '_' . rand(50000, 60000);
        return $this->render('pages/index', [
            "norobot3" => $nobot,
            "norobot2" => time(),
            "norobot" => md5($nobot),

        ]);
    }

    public function legal(): Response
    {
        return $this->render('pages/legal');
    }
}
