<?php

namespace App\Controller;

use \Core\Controller\Controller;

class CartController extends Controller
{
    public function __construct()
    {
        $this->loadModel('OrderLine');
    }

    public function index(): string
    {
        $products = $this->OrderLine->getLinesWithProducts();

        $priceTotalHT = 0;
        foreach ($products as $key => $value) {
            $priceTotalHT += ($value->getPriceHt * $value->getBeerQty());
        }
        
        return $this->render('cart/index', [
            'products' => $products,
            'priceTotalHT' => $priceTotalHT
        ]);
    }

    /*
    * Ajax Method
    */
    public function getProductsInCart(): string
    {
        $_SESSION['cartNumber'] = $this->OrderLine->getProductsInCart();
        return $_SESSION['cartNumber'];
    }

    public function updateCart()
    {
        if (count($_POST) > 0) {
            $id = htmlspecialchars($_POST['id']);
            $qty = htmlspecialchars($_POST['qty']);
            
            if ($this->OrderLine->update($id, 'id', ['qty' => $qty])) {
                echo 'OK';
                die;
            } else {
                return false;
            }
        }
        header('location: /boutique/panier');
        exit();
    }

    public function delete()
    {
        if (count($_POST) > 0) {
            $id = htmlspecialchars($_POST['id']);
            if ($this->OrderLine->delete($id)) {
                echo 'OK';
                die;
            } else {
                return false;
            }
        }
    }
}
