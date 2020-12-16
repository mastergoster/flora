<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;
use Core\Controller\FormController;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class AdminComptaController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $this->loadModel("comptaLines");
        $this->loadModel("comptaNdf");
        $this->loadModel("users");
    }


    public function index(): Response
    {

        $items = $this->comptaLines->all(true, "date_at");
        $return["achats"] = 0;
        $return["recettes"] = 0;
        foreach ($items as $item) {
            $return["recettes"] += $item->getCredit();
            $return["achats"] += $item->getDebit();
        }

        $items = $this->comptaNdf->all();
        $return["achatsndf"] = 0;
        $return["recettesndf"] = 0;
        foreach ($items as $item) {
            $return["recettesndf"] += $item->getCredit();
            $return["achatsndf"] += $item->getDebit();
        }
        return $this->render(
            "admin/compta/panel",
            [
                "items" => $return
            ]
        );
    }

    public function ligne(): Response
    {

        $items = $this->comptaLines->all("desc");
        return $this->render(
            "admin/compta/ligne",
            [
                "items" => $items
            ]
        );
    }

    public function add(): Response
    {
        $form = new FormController();
        $form->field("desc", ["require"]);
        $form->field("credit", ["require", "int"]);
        $form->field("debit", ["require", "int"]);
        $form->field("date_at", ["require"]);
        $error = $form->hasErrors();
        if ($error) {
            return $this->jsonResponse(["err" => $error]);
        }
        $datas = $form->getDatas();
        $datas["date_at"] = str_replace("T", " ", $datas["date_at"]) . ":00";

        $datas["created_at"] = date("Y-m-d h:i:s");
        $this->comptaLines->create($datas);
        $datas["id"] = $this->comptaLines->lastInsertId();
        return $this->jsonResponse($datas);
    }

    public function ndf(): Response
    {
        $form = new FormController();
        $form->field("desc", ["require"]);
        $form->field("debit", ["require", "int"]);
        $form->field("credit", ["require", "int"]);
        $form->field("id_users", ["require"]);
        $error = $form->hasErrors();
        if (!$error) {
            $datas = $form->getDatas();
            $datas["created_at"] = date("Y-m-d h:i:s");
            if ($this->comptaNdf->create($datas)) {
                if ($datas["debit"] < 0.001) {
                    $id = $this->comptaNdf->lastInsertId();
                    $datas["desc"] = "NDF n°$id : " . $datas["desc"];
                    $datas["date_at"] = $datas["created_at"];
                    $credit = $datas["credit"];
                    $debit = $datas["debit"];
                    $datas["debit"] = $credit;
                    $datas["credit"] = $debit;
                    unset($datas["id_users"]);
                    $this->comptaLines->create($datas);
                }
            } else {
                $this->messageFlash()->error("erreur d'enregistrement");
            }
        }
        $users = TableauController::assocId($this->users->all());
        $ndfs = $this->comptaNdf->all();
        return $this->render("admin/compta/ndf", ["users" => $users, "items" => $ndfs]);
    }
}
