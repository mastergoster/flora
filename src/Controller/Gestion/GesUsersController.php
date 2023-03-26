<?php

namespace App\Controller\Gestion;

use \Core\Controller\Controller;
use Core\Controller\FormController;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\Helpers\HController;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class GesUsersController extends Controller
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

    public function user(int $id): Response
    {

        $user = $this->users->find($id);
        $formUpdate = new FormController();
        $formUpdate->field("first_name");
        $formUpdate->field("last_name");
        $formUpdate->field("street");
        $formUpdate->field("city");
        $formUpdate->field("postal_code");
        $formUpdate->field("desc");
        $formUpdate->field("society");
        if ($user->getVerify() == "1") {
            $formUpdate->field("pin", ["ExactLength" => 4, "int"]);
        }
        $errors = [];
        if ($this->request()->query->has("user")) {
            $errors =  $formUpdate->hasErrors();
            if (!isset($errors["post"])) {
                $datas = $formUpdate->getDatas();
                if (!$errors) {
                    $this->users->update($user->getId(), "id", $datas);
                }
            }
            $user = $this->users->find($id);
        }

        $invoces = $this->invoces->allActivateByUser($user->getId());
        $userRoles = $this->rolesLog->findAll($user->getId(), "id_users", "DESC", "created_at");
        $roles = TableauController::assocId($this->roles->all());
        return $this->render(
            "gestion/edit",
            [
                "user" => $user,
                "invoces" => $invoces,
                "userRoles" => $userRoles,
                "roles" => $roles,
            ]
        );
    }
}
