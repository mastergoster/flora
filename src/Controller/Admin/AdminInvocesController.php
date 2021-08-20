<?php

namespace App\Controller\Admin;

use App\App;
use \Core\Controller\Controller;
use App\Services\InvocesServices;
use Core\Controller\FormController;
use App\Model\Table\InvocesLinesTable;
use App\Model\Entity\InvocesLinesEntity;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class AdminInvocesController extends Controller
{
    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $this->loadModel('invoces');
        $this->loadModel('users');
        $this->loadModel('invocesLines');
        $this->loadModel('products');
        $this->loadModel('comptaLines');
    }

    public function all(): Response
    {
        $invocesServices = new InvocesServices;
        $formInvoce = new FormController();
        $formInvoce->field("id_user", ["require"]);
        $formInvoce->field("date_at", ["require", "date"]);
        $errors =  $formInvoce->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $formInvoce->getDatas();
            if (!$errors) {
                $invocesServices->getNewInvoce($datas);
            }
        }
        if ($this->request()->query->has("delete")) {
            $form = new FormController();
            $form->field("id", ["require"]);
            $errorsDelete =  $form->hasErrors();
            if (!isset($errorsDelete["post"])) {
                $datasDel = $form->getDatas();
                if (!$errorsDelete) {
                    $inv = $this->invoces->find($datasDel["id"]);
                    if ($inv->getActivate() == 0) {
                        if (\substr($inv->getRef(), 0, 5) == "FACT_") {
                            $this->messageFlash()->error("la facture a deja une ref comptable il faut faire un avoir");
                        } else {
                            $this->messageFlash()->success("la facture est supprimé");
                            $this->invoces->delete($datasDel["id"]);
                        }
                    } else {
                        if ($this->comptaLines->find($inv->getRef(), "desc")) {
                            $this->messageFlash()->error("la facture payer impossible de supprimer");
                        } else {
                            $this->invoces->update($datasDel["id"], "id", ["activate" => 0]);
                            $this->messageFlash()->success("la facture est désactivé");
                        }
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

    public function single($id): Response
    {

        $formInvoce = new FormController();
        $formInvoce->field("id_products");
        $formInvoce->field("qte");
        $formInvoce->field("discount");
        $errors =  $formInvoce->hasErrors();
        if (!isset($errors["post"])) {
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
            return $this->redirect("adminInvocePdf", ["id" => $id]);
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

    public function validate($id): Response
    {
        $invoces = $this->invoces->find($id);
        if ($invoces) {
            $service = new InvocesServices();
            $service->activate($invoces);
            $this->invoces->updateByClass($invoces);
        }
        $this->messageFlash()->success("la facture est activé");
        return $this->redirect('adminInvoces');
    }

    public function actualise($id): Response
    {
        $invoces = $this->invoces->find($id);
        $uid = \uniqid();
        while ($this->invoces->find($uid, "ref_stripe_token")) {
            $uid = \uniqid();
        }
        $invoces->setRefStripeToken($uid);
        $this->invoces->updateByClass($invoces);
        if ($invoces->getActivate() == 1) {
            $file = App::getInstance()->rootFolder() . "/files/user/invoce/" . $invoces->getRef() . ".pdf";
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return $this->redirect('adminInvoces');
    }

    public function invocePdf($id): Response
    {
        $invoces = $this->invoces->find($id);
        $user = $this->users->find($invoces->getIdUsers());
        $invoces->setInvocesLines($this->invocesLines->findall($id, "id_invoces"));
        return $this->renderPdf("user/invoce", ["invoce" => $invoces, "user" => $user, "title" => $invoces->getRef()]);
    }

    public function products(): Response
    {
        $form = new FormController();
        $form->field("ref", ["require"]);
        $form->field("name", ["require"]);
        $form->field("desc", ["require"]);
        $form->field("price", ["require", "int"]);
        $form->field("activate", ["require", "boolean"]);
        $errors =  $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            if (!$errors) {
                $this->products->create($datas);
            }
        }
        return $this->render('admin/products', ["form" => $form->html(),  'items' => $this->products->all()]);
    }
}
