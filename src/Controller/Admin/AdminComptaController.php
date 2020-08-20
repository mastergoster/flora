<?php

namespace App\Controller\Admin;

use App\Model\Entity\RecapConsoEntity;
use \Core\Controller\Controller;
use Core\Controller\FormController;

class AdminComptaController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile');
        }
        $this->loadModel("comptaLigne");
        $this->loadModel("comptaNdf");
        $this->loadModel("user");
    }

    public function index()
    {

        $items = $this->comptaLigne->all(true, "date");
        $return["achats"] = 0;
        $return["recettes"] = 0;
        foreach ($items as $item) {
            $return["recettes"] += $item->getCredit();

            $return["achats"] += $item->getDebit();
        }
        return $this->render(
            "admin/compta/panel",
            [
                "items" => $return
            ]
        );
    }

    public function ligne()
    {

        $items = $this->comptaLigne->all("desc");
        return $this->render(
            "admin/compta/ligne",
            [
                "items" => $items
            ]
        );
    }

    public function add()
    {
        $form = new FormController();
        $form->field("description", ["require"]);
        $form->field("credit", ["require", "int"]);
        $form->field("debit", ["require", "int"]);
        $form->field("date", ["require"]);
        $error = $form->hasErrors();
        if ($error) {
            return $this->jsonResponse(["err" => $error]);
        }
        $datas = $form->getDatas();
        $datas["created_at"] = date("Y-m-d h:i:s");
        $this->comptaLigne->create($datas);
        $datas["id"] = $this->comptaLigne->lastInsertId();
        return $this->jsonResponse($datas);
    }

    public function ndf()
    {
        $form = new FormController();
        $form->field("description", ["require"]);
        $form->field("id_user", ["require"]);
        $form->field("id_ligne", ["require"]);
        $error = $form->hasErrors();
        if (!$error) {
            $datas = $form->getDatas();
            $datas["created_at"] = date("Y-m-d h:i:s");
            $this->comptaNdf->create($datas);
        }
        if (isset($_POST["id"]) && isset($_POST["paye_at"])) {
            $this->comptaNdf->update($_POST["id"], "id", ["paye_at" => date("Y-m-d H:i:s")]);
        }
        $lignes = $this->comptaLigne->all();
        $users = $this->users->all();
        $ndfs = $this->comptaNdf->all();
        foreach ($lignes as $key => $ligne) {
            foreach ($ndfs as $ndf) {
                if ($ligne->getId == $ndf->getIdLigne) {
                    unset($lignes[$key]);
                }
            }
        }
        return $this->render("admin/compta/ndf", ["users" => $users, "lignes" => $lignes, "items" => $ndfs]);
    }
}
