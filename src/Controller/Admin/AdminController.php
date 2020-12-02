<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;
use Core\Controller\FormController;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            return $this->redirect('userProfile');
        }
        $this->loadModel('users');
        $this->loadModel('hours');
        $this->loadModel('packages');
        $this->loadModel('packagesLog');
        $this->loadModel('roles');
        $this->loadModel('rolesLog');
        $this->loadModel("messages");
    }

    public function panel(): Response
    {

        $users = [];
        return $this->render(
            "admin/panel",
            [
                "users" => $users
            ]
        );
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
            $value->presence = $this->hours->presence($key);
        }


        return $this->render(
            "admin/users",
            [
                "users" => $users
            ]
        );
    }


    public function user($id): Response
    {
        if (count($_POST) >= 1) {
            if (isset($_POST["idForfait"])) {
                $data = [
                    "id_users" => $id,
                    "id_packages" => $_POST["idForfait"]
                ];
                $this->packagesLog->create($data);
            } elseif (isset($_POST["idForfaitDel"])) {
                $this->packagesLog->delete($_POST["idForfaitDel"]);
            } elseif (isset($_POST["role"])) {
                $this->rolesLog->update($_POST["roleLineId"], "id", ["updated_at" => date("Y-m-d h:i:s")]);
                $this->rolesLog->create(["id_roles" => $_POST["role"], "id_users" => $_POST["id"]]);
            } else {
                $data = $_POST;
                unset($data['id']);
                $this->users->update($id, "id", $data);
            }
        }
        $users = $this->users->find($id);
        $users->presence = $this->hours->presence($users->getId());
        $recap = new RecapConsoEntity($id);
        $forfait = TableauController::assocId($this->packages->all());
        $userRole = $this->rolesLog->findAll($users->getId(), "id_users", "DESC", "created_at");
        $roles = TableauController::assocId($this->roles->all());
        return $this->render(
            "admin/user",
            [
                "users" => $users,
                "userRoles" => $userRole,
                "recap" => $recap,
                "forfaits" => $forfait,
                "forfaitsUser" => $this->packagesLog->findAll($id, 'id_users'),
                "roles" => $roles
            ]
        );
    }

    public function products(): Response
    {
        $form = new FormController();
        $form->field('name', ["require"]);
        $form->field('duration', ["require", 'int']);
        $form->field('price', ["require", 'int']);
        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            if (!$errors) {
                $this->packages->create($datas);
            }
        }

        $users = $this->packages->all();
        return $this->render(
            "admin/packages",
            [
                "items" => $users,
                "form"  => $form->html()
            ]
        );
    }

    public function orders(): Response
    {
        $users = [];
        return $this->render(
            "admin/orders",
            [
                "items" => $users
            ]
        );
    }

    public function role($id): Response
    {

        $users = $this->roles->find($id);
        return $this->render(
            "admin/role",
            [
                "items" => $users
            ]
        );
    }
    public function roles(): Response
    {

        $users = $this->roles->all();
        return $this->render(
            "admin/roles",
            [
                "items" => $users,
            ]
        );
    }

    public function modifHeure($id): Response
    {

        if (count($_POST) >= 1) {
            if (isset($_POST["date"]) && isset($_POST["id"])) {
                preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $_POST["date"], $matches);
                if ($matches) {
                    $data =  ["created_at" => $_POST["date"]];
                    $this->hours->update($_POST["id"], "id", $data);
                }
            } elseif (isset($_POST["id"])) {
                $this->hours->delete($_POST["id"]);
            } elseif (isset($_POST["date"])) {
                preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $_POST["date"], $matches);
                if ($matches) {
                    $data = ["id_users" => $id, "created_at" => $_POST["date"]];
                    $this->hours->create($data);
                }
            }
        }
        $heures = $this->hours->findAll($id, 'id_users', "asc", "created_at");
        $recap = [];
        foreach ($heures as $heure) {
            $recap[$heure->getCreatedAt()->format("Y-m-d")][] = $heure;
        }
        krsort($recap);
        return $this->render(
            "admin/heures",
            [
                "items" => $recap,
            ]
        );
    }

    public function messages(): Response
    {
        $messages = $this->messages->all();
        return $this->render(
            "admin/messages",
            [
                "items" => $messages,
            ]
        );
    }
}
