<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;

class AdminCoreController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile');
        }
    }

    public function update()
    {
        if (\getenv("ENV_DEV")) {
            return dd("mode dev impossible de faire ceci");
        }
        exec("cd .. && git pull", $returns);
        exec("cd .. && composer update", $returns2);
        dump($returns);
        dd($returns2);
    }
}
