<?php

namespace Core\Controller;

use App\App;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;

class ChargeController extends Controller
{
    private $user;
    private $token = "";
    private $url = "";
    private $numero;
    private $temporaire = false;

    public function __construct()
    {
        $this->loadModel("Users");
        $this->loadModel("invoces");
        $this->loadModel("comptaLines");

        $this->token = $this->getConfig("tokenSms");
        $this->url = $this->getConfig("urlSms");
    }

    public function create(int $id): Response
    {
        $invoce = $this->invoces->findActivate($id, "id");

        if (!$invoce) {
            $this->messageFlash()->error("action non permise");
            return $this->redirect("userInvoces");
        }

        if (!$this->session()->has("users")) {
            // if(!isset($_GET["token"]) || $_GET["token"] != $invoce->securityToken()){
            //     dd($invoce->securityToken());
            //     dd("pas cool");
            //     $this->messageFlash()->error("action non permise");
            //     return $this->redirect("userInvoces");
            // }
            $this->temporaire = true ;
            $user = $this->Users->find($invoce->getIdUsers(), "id");
        }else{
            $user = $this->session()->get("users");
        }
        
        $invoce = $this->invoces->findActivate($id, "id");
        $totalPaid = 0;
        foreach ($this->comptaLines->findAll($invoce->getRef(), 'desc') as $line) {
            $totalPaid = $totalPaid + $line->getCredit() - $line->getDebit();
        }
        $invoce->getPrice();
        if (!$invoce || $user->getId() != $invoce->getIdUsers() || $invoce->getPrice() <= $totalPaid) {
            $this->messageFlash()->error("action non permise");
            return $this->redirect("userInvoces");
        }

        Stripe::setApiKey(App::getInstance()->getConfig("STRIPE_KEY"));
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => [
                'card',
            ],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'images' => [App::getInstance()->getConfig("siteUrl") . '/images/image-ol.jpg'],
                            'name' => 'FACTURE ' . $invoce->getRef(),
                        ],
                        'unit_amount' => $invoce->getPrice() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => App::getInstance()->getConfig("siteUrl") .
                $this->generateUrl("chargeReturn", ['id' => $invoce->getId(), "slug" => $invoce->getRefStripeToken()]),
            'cancel_url' => App::getInstance()->getConfig("siteUrl") .
                $this->generateUrl("chargeReturn", ['id' => $invoce->getId(), "slug" => "cancel"]),
        ]);
        PaymentIntent::update($checkout_session->payment_intent, ["description" => $invoce->getRef()]);
        return $this->redirect($checkout_session->url);
    }

    public function chargeReturn(int $id, string $slug): Response
    {
        if ($slug == "cancel") {
            $this->messageFlash()->error("Votre paiement a été annulé");
            return $this->redirect("userInvoces");
        }
        $invoce = $this->invoces->find($id, "id");
        if ($invoce->getRefStripeToken() != $slug) {
            $this->messageFlash()->error("Votre paiement a été annulé");
            return $this->redirect("userInvoces");
        }
        $datas["desc"] = $invoce->getRef();
        $datas["credit"] = $invoce->getPrice();
        $datas["debit"] = 0;
        $datas["date_at"] = date("Y-m-d H:i:s");
        $this->comptaLines->create($datas);
        $this->messageFlash()->success("Votre paiement a été effectué avec succès");
        return $this->redirect("userInvoces");
    }
}
