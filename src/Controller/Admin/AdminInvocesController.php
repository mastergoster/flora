<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;
use App\Services\InvocesServices;
use Core\Controller\FormController;
use App\Model\Table\InvocesLinesTable;
use App\Model\Entity\InvocesLinesEntity;
use Core\Controller\Helpers\TableauController;


class AdminInvocesController extends Controller
{
    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile');
        }
        $this->loadModel('invoces');
        $this->loadModel('users');
        $this->loadModel('invocesLines');
        $this->loadModel('products');
    }

    public function all()
    {
        $invocesServices = new InvocesServices;
        $formInvoce = new FormController();
        $formInvoce->field("id_user", ["require"]);
        $formInvoce->field("date_at", ["require", "date"]);
        $errors =  $formInvoce->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $formInvoce->getDatas();
            if (!$errors) {
                $invocesServices->getNewInvoce($datas);
            }
        }
        if ($this->request()->query->has("delete")) {
            $form = new FormController();
            $form->field("id", ["require"]);
            $errorsDelete =  $form->hasErrors();
            if ($errorsDelete["post"] != ["no-data"]) {
                $datasDel = $form->getDatas();
                if (!$errorsDelete) {
                    if ($this->invoces->find($datasDel["id"])->getActivate() == 0) {
                        $this->invoces->delete($datasDel["id"]);
                    } else {
                        $this->invoces->update($datasDel["id"], "id", ["activate" => 0]);
                    }
                }
            }
        }
        $invoces = $this->invoces->all();
        $users = TableauController::assocID($this->users->all());
        return $this->render(
            "admin/invoces/invoces",
            [
                "items" => $invoces,
                "users" => $users,
                "form" => $formInvoce->html()
            ]
        );
    }

    public function single($id)
    {

        $formInvoce = new FormController();
        $formInvoce->field("id_products");
        $formInvoce->field("qte");
        $formInvoce->field("discount");
        $errors =  $formInvoce->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $formInvoce->getDatas();
            if (!$errors) {
                if ($datas["id_products"]) {
                    if ($product = $this->products->findForInvoce($datas["id_products"])) {
                        $product->setIdInvoces($id);
                        $product->setIdProducts($product->getId());
                        $product->setQte($datas["qte"] ?: 1);
                        $product->setDiscount($datas["discount"] ?: 0);
                        $product->setId(null);
                        $product->activate = null;
                        $this->invocesLines->create($product, true);
                    }
                }
            }
        }
        $invoces = $this->invoces->find($id);
        if ($invoces->getActivate()) {
            $this->redirect("adminInvocePdf", ["id" => $id]);
        }
        $users = $this->users->find($invoces->getIdUsers());
        $invocesLines = $this->invocesLines->findAll($id, 'id_invoces');
        $products = $this->products->all();

        if ($invoces->getActivate()) {
            $formInvoce = "";
        } else {
            $formInvoce = $formInvoce->html();
        }
        return $this->render(
            "admin/invoces/invoce",
            [
                "item" => $invoces,
                "users" => $users,
                "form" => $formInvoce,
                'invocesLines' => $invocesLines,
                'products' => $products
            ]
        );
    }

    public function validate($id)
    {
        $invoces = $this->invoces->find($id);
        if ($invoces) {
            $service = new InvocesServices();
            $service->activate($invoces);
            $this->invoces->updateByClass($invoces);
            // dd($invoces);
        }
        $this->redirect('adminInvoces');
    }

    public function invocePdf($id)
    {
        $invoces = $this->invoces->find($id);
        $user = $this->users->find($invoces->getIdUsers());
        $invoces->setInvocesLines($this->invocesLines->findall($id, "id_invoces"));
        return $this->renderPdf("user/invoce", ["invoce" => $invoces, "user" => $user, "title" => $invoces->getRef()]);
    }

    public function products()
    {
        $form = new FormController();
        $form->field("ref", ["require"]);
        $form->field("name", ["require"]);
        $form->field("desc", ["require"]);
        $form->field("price", ["require", "int"]);
        $form->field("activate", ["require", "boolean"]);
        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            if (!$errors) {
                $this->products->create($datas);
            }
        }
        return $this->render('admin/products', ["form" => $form->html(),  'items' => $this->products->all()]);
    }
}
