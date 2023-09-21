<?php

namespace App\Controller\Gestion;

use App\App;
use \Core\Controller\Controller;
use App\Services\InvocesServices;
use Core\Controller\FormController;
use App\Model\Entity\RecapConsoEntity;
use App\Model\Entity\ComptaLinesEntity;
use Core\Controller\Helpers\HController;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class GesInvocesController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->accessRole(40)) {
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
        $this->loadModel("invocesLines");
        $this->loadModel("comptaLines");
        $this->loadModel("products");
    }

    public function invoces(): Response
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }

        $invoces = $this->invoces->all();
        $years = [];
        $yearNow = date("Y");
        for ($i = 2020; $i <= $yearNow; $i++) {
            $years[] = $i;
        }

        return $this->render(
            "gestion/invoces",
            [
                "invoces" => $invoces,
                "years" => $years,
                "yearNow" => $yearNow,
            ]
        );
    }


    public function invoce($id): Response
    {
        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
        }
        $form = new FormController();
        $form->field("id", ["require"]);
        $form->field("action", ["require"]);
        $form->field("data");
        $form->field("data-qte");
        $form->field("data-desc");
        $form->field("invoceId", ["require"]);
        $errors =  $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            if ($datas["invoceId"] == $id) {
                if (!$errors) {
                    switch ($datas["action"]) {
                        case 'data-desc':
                            $this->invocesLines->update($datas["id"], 'id', ["desc" => $datas["data-desc"]]);
                            break;
                        case 'qte':
                            $qte = $datas["data"]>=1?$datas["data"]:1;
                            $this->invocesLines->update($datas["id"], 'id', ["qte" => $qte]);
                            break;
                        case 'delete':
                            $this->invocesLines->delete($datas["id"]);
                            break;
                        case 'addline':
                            $product = $this->products->findForInvoce($datas["data"]);
                            $update = $this->invocesLines->find(["id_products" => $product->getId(), "id_invoces" => $datas["invoceId"]]);
                            if ($product && !$update) {
                                $product->setIdInvoces($datas["invoceId"]);
                                $product->setIdProducts($product->getId());
                                $product->setQte($datas["data-qte"]);
                                $product->setDiscount(0);
                                $product->setId(null);
                                $product->activate = null;
                                $this->invocesLines->create($product, true);
                            }elseif($update){
                                $this->invocesLines->update($update->getId(), "id", ["qte" => $update->getQte() + $datas["data-qte"]]);
                            }
                            break;
                    }
                }
            }
        }

        $invoce = $this->invoces->findActivate($id, "id");
        if (!$invoce) {
            $invoce = $this->invoces->findById($id);
            if (!$invoce) {
                $this->messageFlash()->error("action non permise");
                return $this->redirect("gestion_invoces");
            }
            $total = 0;
            foreach ($invoce->getInvocesLines() as $line) {
                $total += ($line->getPrice() * $line->getQte()) - $line->getDiscount();
            }
            return $this->render(
                "gestion/invoce",
                [
                    "invoce" => $invoce,
                    'products' => $this->products->all(),
                    "total" => $total
                ]
            );
        }
        $user = $this->users->find($invoce->getIdUsers());
        /**
         * no cache 
         */
        if ($invoce->getActivate() == 1) {
            $file = App::getInstance()->rootFolder() . "/files/user/invoce/" . $invoce->getRef() . ".pdf";
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return $this->renderPdf("user/invoce", ["invoce" => $invoce, "user" => $user, "title" => $invoce->getRef()]);
    }

    public function validateInvoce($id): Response
    {
        $invoces = $this->invoces->find($id);
        $invoces->setDateAt(date("Y-m-d H:i:s"));
        if ($invoces) {
            $service = new InvocesServices();
            $service->activate($invoces);
            $this->invoces->updateByClass($invoces);
        }
        $this->messageFlash()->success("la facture est activé");
        return $this->redirect('gestion_invoces');
    }

    public function newInvoce($id): Response
    {
        $invocesServices = new InvocesServices;
        if (is_numeric($id)) {
            $data = ["date_at" => date("Y-m-d 09:00:00"), "id_user" => $id];

            return $this->redirect(
                'GetInvoce',
                ["id" => $invocesServices->getNewInvoce($data)->getId()]
            );
        }
        return $this->redirect('gestion_invoces');
    }

    public function deleteInvoce(): Response
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $form = new FormController();
        $form->field("id", ["require"]);
        $errors =  $form->hasErrors();

        if (!isset($errors["post"])) {
            $datasDel = $form->getDatas();
            if (!$errors) {
                $inv = $this->invoces->find($datasDel["id"]);
                if ($inv->getActivate() == 0) {
                    $this->invoces->delete($datasDel["id"]);
                }
            }
        }
        return $this->redirect('gestion_invoces');
    }


    public function payeInvoce()
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }
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
