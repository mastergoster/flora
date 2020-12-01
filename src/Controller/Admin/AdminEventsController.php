<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;
use Core\Controller\FormController;

class AdminEventsController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            return $this->redirect('userProfile');
        }
        $this->loadModel("events");
        $this->loadModel("bookingEvents");
    }

    public function events()
    {
        $eventsbooking = $this->bookingEvents->all();
        $eventsbyid = [];
        foreach ($eventsbooking as $value) {
            $eventsbyid[$value->getIdEvents()][] = $value;
        }
        foreach ($eventsbyid as $key => $values) {
            $eventsbyid[$key]["nb"] = count($values);
        }
        $form = new FormController();
        $form->field("id", ["require", "int"]);
        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            if (!$errors) {
                $this->events->delete($datas["id"]);
            }
        }
        $items = $this->events->all(true, "date_at");
        return $this->render(
            "admin/events",
            [
                "items" => $items,
                "count" => $eventsbyid
            ]
        );
    }

    public function event(?int $id = null)
    {
        $form = new FormController();
        $form->field("title", ["require"]);
        $form->field("slug", ["require"]);
        $form->field("description", ["require"], ["safe" => true]);
        $form->field("cover", ["require"]);
        $form->field("date_at", ["require", "date"]);
        $form->field("publish", ["boolean"]);
        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            if (!$errors) {
                if ($id) {
                    if ($this->events->update($id, 'id', $datas)) {
                        $datas["updated_at"] = date("Y-m-d H:i:s");
                        $this->messageFlash()->success("l'évènement a bien été mis à jour");
                    } else {
                        $this->messageFlash()->error("erreur de mise à jour");
                    }
                } else {
                    if ($this->events->create($datas)) {
                        $this->messageFlash()->success("l'évènement a bien été mis à jour");
                        return $this->redirect('adminEventSingle', ["id" => $this->events->lastInsertId()]);
                    } else {
                        $this->messageFlash()->error("erreur de mise à jour");
                    }
                }
            }
            foreach ($errors as $errorArray) {
                foreach ($errorArray as $error) {
                    $this->messageFlash()->error($error);
                }
            }
        }
        $form->hydrate($this->events->find($id));
        return $this->render(
            "admin/event",
            [
                "form" => $form->html()
            ]
        );
    }
}
