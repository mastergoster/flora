<?php

namespace App\Controller\Gestion;

use \Core\Controller\Controller;
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
}
