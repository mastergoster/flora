<?php

namespace App\Controller\Gestion;

use App\Model\Entity\ComptaLinesEntity;
use \Core\Controller\Controller;
use Core\Controller\FormController;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\Helpers\HController;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class GesInvocesController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $this->loadModel('users');
        $this->loadModel('hours');
        $this->loadModel('packages');
        $this->loadModel('packagesLog');
        $this->loadModel('roles');
        $this->loadModel('rolesLog');
        $this->loadModel("messages");
        $this->loadModel("invoces");
        $this->loadModel("comptaLines");
    }

    public function invoces(): Response
    {

        $invoces = $this->invoces->all();
        return $this->render(
            "gestion/invoces",
            [
                "invoces" => $invoces
            ]
        );
    }


    public function invoce($id): Response
    {
        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
        }

        $invoce = $this->invoces->findActivate($id, "id");
        if (!$invoce) {
            $this->messageFlash()->error("action non permise");
            return $this->redirect("gestion_invoces");
        }
        $user = $this->users->find($invoce->getIdUsers());
        return $this->renderPdf("user/invoce", ["invoce" => $invoce, "user" => $user, "title" => $invoce->getRef()]);
    }


    public function payeInvoce()
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }

        if ($this->request()->request->has("id")) {
            $form = new FormController();
            $form->field("id", ["require"]);
            $errorsPaye =  $form->hasErrors();
            if (!isset($errorsPaye["post"])) {
                $datasDel = $form->getDatas();
                if (!$errorsPaye) {
                    $inv = $this->invoces->find($datasDel["id"]);

                    $data = [];
                    $data["desc"] = $inv->getRef();
                    $data["credit"] = $inv->getPrice();
                    $data["debit"] = 0;
                    $data["date_at"] = date("Y-m-d h:i:s");
                    $data["created_at"] = date("Y-m-d h:i:s");
                    $this->comptaLines->create($data);
                }
            }
        }
        return $this->redirect("gestion_invoces");
    }
}
