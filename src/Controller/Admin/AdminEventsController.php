<?php

namespace App\Controller\Admin;

use App\Model\Entity\RecapConsoEntity;
use \Core\Controller\Controller;
use Core\Controller\FormController;

class AdminEventsController extends Controller
{

    public function __construct()
    {
        $this->security()->onlyAdmin();
        $this->loadModel("events");
    }

    public function events()
    {

        $items = $this->events->all(true, "date_at");
        return $this->render(
            "admin/compta/panel",
            [
                "items" => $items
            ]
        );
    }
}
