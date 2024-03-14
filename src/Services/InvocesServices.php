<?php

namespace App\Services;

use App\App;
use Core\Controller\Controller;
use App\Model\Entity\InvocesEntity;
use Core\Controller\EmailController;

class InvocesServices extends Controller
{
    public function __construct()
    {
        $this->loadModel("invoces");
        $this->loadModel("invocesLines");
        $this->loadModel("users");
    }

    public function getNewInvoce(array $datas)
    {

        if (!\array_key_exists("id_user", $datas)) {
            return false;
        }
        $newInvoces = new InvocesEntity;
        $newInvoces->setIdUsers($datas["id_user"]);
        if (\array_key_exists("date_at", $datas)) {
            $newInvoces->setDateAt($datas["date_at"]);
        }
        $newInvoces->setRef("PROV_" . date("YmdHis"));
        $uid = uniqid();
        if (!$this->invoces->find($uid, "ref_stripe_token")) {
            $newInvoces->setRefStripeToken($uid);
        }
        if (!$this->invoces->create($newInvoces, true)) {
            return false;
        }
        $newInvoces->setId($this->invoces->lastInsertId());
        return  $newInvoces;
    }


    public function numfact($validate = false)
    {
        $ref = date("Ymd") . "0001";
        $prefix = $validate ? "FACT_" : "PROV_";
        $invoces = $this->invoces->findAll($validate, "activate", true, 'ref');
        foreach ($invoces as $invoce) {
            if (substr($invoce->getRef(), -4, 4) != substr($prefix . $ref, -4, 4)) {
                throw new \Exception("non conformité des numero de facture", 1);
            } else {
                $ref += 1;
            }
        }
        return $prefix . $ref;
    }

    public function activate(InvocesEntity $invoce)
    {
        $lines = $this->invocesLines->findAll($invoce->getID(), "id_invoces");
        foreach ($lines as $line) {
            $invoce->setPrice($invoce->getPrice() + (($line->getPrice() * $line->getQte()) - $line->getDiscount()));
        }
        if ($invoce->getPrice() == 0) {
            return false;
        }
        $invoce->setActivate(true);
        if (\substr($invoce->getRef(), 0, 5) == "PROV_") {
            $invoce->setRef($this->numfact(true));
        }


        //dd($this->numfact(true));
        $invoce->setUpdatedAt(date("Y-m-d H:i:s"));
        $this->invoces->updateByClass($invoce);
        $invoces = $this->invoces->findActivate($invoce->getId());
        $user = $this->users->find($invoce->getIdUsers());
        $mail = new EmailController();
        $mail->object(getenv('siteName') . ' - Vous avez une nouvelle facture sur votre espace')
            ->to($user->getEmail())
            ->message('newInvoce', compact('user', 'invoce'))
            ->send();
        return true;
    }
}
