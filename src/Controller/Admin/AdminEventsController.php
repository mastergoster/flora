<?php

namespace App\Controller\Admin;

use App\Model\Entity\RecapConsoEntity;
use \Core\Controller\Controller;
use Core\Controller\FormController;

class AdminEventsController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile');
        }
        $this->loadModel("events");
    }

    public function events()
    {

        $items = $this->events->all(true, "date_at");
        return $this->render(
            "admin/events",
            [
                "items" => $items
            ]
        );
    }

    public function event($id)
    {

        $items = $this->events->find($id);
        return $this->render(
            "admin/event",
            [
                "items" => $items
            ]
        );
    }
}
