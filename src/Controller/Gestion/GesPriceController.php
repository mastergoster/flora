<?php

namespace App\Controller\Gestion;

use \Core\Controller\Controller;
use Core\Controller\FormController;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\Helpers\HController;
use Core\Controller\Helpers\TableauController;
use App\Model\Entity\ProductsEntity;
use Symfony\Component\HttpFoundation\Response;

class GesPriceController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->accessRole(50)) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $this->loadModel('packages');
        $this->loadModel('products');
    }

    public function all(): Response
    {

        return $this->render(
            "bureau/prices",
            [
                "products" => $this->products->all()
            ]
        );
    }

    public function modif($id)
    {
        $product = $this->products->find($id);
        if (!$product) {
            $id = null;
        }
        $form = new FormController();
        $form->field("ref", ["require"]);
        $form->field("name", ["require"]);
        $form->field("desc", ["require"]);
        $form->field("price", ["require", "int"]);
        $form->field("activate", ["boolean"]);
        $errors =  $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            if (!$errors) {
                if ($id===null) {
                    $product = $this->products->create($datas);
                }else{
                    $product->hydrate($datas);
                    $this->products->updateByClass($product);
                }
                
            }
        }

        return $this->render(
            "bureau/priceEdit",
            [
                "item" => $product
            ]
        );
    }
}
