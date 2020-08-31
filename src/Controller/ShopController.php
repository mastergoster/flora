<?php

namespace App\Controller;

use \Core\Controller\Controller;
use Core\Controller\SmsController;
use Core\Controller\EmailController;
use Core\Extension\Twig\LinkExtension;

class ShopController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return $this->render('shop/index');
    }

    public function all()
    {

        $beers = $this->beer->all();

        return $this->render('shop/boutique', [
            'beers' => $beers
        ]);
    }

    public function purchaseOrder()
    {

        if (count($_POST) > 0) {
            foreach ($_POST['qty'] as $key => $value) {
                if ($value > 0) {
                    $ids[] = $key;
                    $qty[] = $value;
                }
            }
            $ids = implode(',', $ids);

            $beers = $this->beer->getAllInIds($ids);

            $orderTotal = 0;
            foreach ($beers as $key => $value) {
                $orderTotal += $value->getPriceHt() * constant('TVA') * $qty[$key];
            }

            return $this->render('shop/confirmationDeCommande', [
                'beers' => $beers,
                'data' => $_POST,
                'qty' => $qty,
                'order' => $orderTotal
            ]);
        }

        $beers = $this->beer->all();

        return $this->render('shop/bondecommande', [
            'beers' => $beers
        ]);
    }

    public function contact()
    {

        return $this->render('shop/contact', []);
    }
}
