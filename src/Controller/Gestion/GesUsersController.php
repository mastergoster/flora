<?php

namespace App\Controller\Gestion;

use \Core\Controller\Controller;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\Helpers\HController;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class GesUsersController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
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

    public function users(): Response
    {

        $users = TableauController::assocId($this->users->all());
        $roles = TableauController::assocId($this->roles->all());
        $rolesLog = $this->rolesLog->all();
        foreach ($rolesLog as $roleLog) {
            $users[$roleLog->getIdUsers()]->role = $roles[$roleLog->getIdRoles()];
        }
        foreach ($users as $key => $value) {
            $value->recap = new RecapConsoEntity($value->getId());
            $value->invoces = $this->invoces->allActivateByUser($value->getId(), "id_users");
            $value->totalInvoce = 0;
            $value->totalPaid = 0;
            foreach ($value->invoces as $invoce) {
                foreach ($this->comptaLines->findAll($invoce->getRef(), 'desc') as $line) {
                    $value->totalPaid = $value->totalPaid + $line->getCredit() - $line->getDebit();
                }
                $value->totalInvoce += $invoce->getPrice();
            }
        }

        return $this->render(
            "gestion/users",
            [
                "users" => $users
            ]
        );
    }
}
