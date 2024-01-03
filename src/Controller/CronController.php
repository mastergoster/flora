<?php

namespace App\Controller;

use \Core\Controller\Controller;
use Core\Controller\Helpers\TableauController;

class CronController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile')->send();
            exit();
        }
        $this->loadModel("invocesLines");
        $this->loadModel("invoces");
        $this->loadModel("packagesLog");
        $this->loadModel("rolesLog");
        $this->loadModel("packages");
        $this->loadModel("hours");
        $this->loadModel("users");
    }

    public function updatePackage()
    {
        $packageOne = $this->invocesLines->findAll(1, "id_products");
        $packageTwo = $this->invocesLines->findAll(16, "id_products");
        $packageThree = $this->invocesLines->findAll(17, "id_products");
        $packageAll = array_merge($packageOne, $packageTwo, $packageThree);
        $userPackage = [];
        foreach ($packageAll as $package) {
            $idUser = $this->invoces->find($package->getIdInvoces())->getIdUsers();
            $idProduct = $package->getIdProducts();
            $userPackage[$idUser][$idProduct]["count"] = $userPackage[$idUser][$idProduct]["count"] ? $userPackage[$idUser][$idProduct]["count"] + 1 : 1;
        }
        $idPackageArray = [1 => 1, 2 => 16, 3 => 17];
        foreach ($userPackage as $id => $package) {
            $pakagesByUsers = $this->packagesLog->findAll($id, "id_users");
            foreach ($pakagesByUsers as $value) {
                $idPackage = $idPackageArray[$value->getIdPackages()];
                $userPackage[$id][$idPackage]["apply"] =
                    $userPackage[$id][$idPackage]["apply"] ? $userPackage[$id][$idPackage]["apply"] + 1 : 1;
            }
        }
        $idPackageArray = [1 => 1, 16 => 2, 17 => 3];
        $create = 0;
        foreach ($userPackage as $id => $package) {
            foreach ($package as $idPackage => $value) {

                while ($value["count"] > $value["apply"]) {
                    $this->packagesLog->create(
                        [
                            "id_users" => $id,
                            "id_packages" => $idPackageArray[$idPackage]
                        ]
                    );
                    $create++;
                    $value["apply"]++;
                }
            }
        }
        dd($create);
    }

    function heurVerify()
    {
        $packages = TableauController::assocId($this->packages->all());
        $hours = $this->hours->all('DESC', "created_at");
        $userHours = [];
        foreach ($hours as $value) {
            $userHours[$value->getIdUsers()][$value->getCreatedAt()->format('Y-m-d')][] = $value->getCreatedAt()->format('H:i:s');
        }
        foreach ($userHours as $id => $value) {
            $pakagesByUsers = $this->packagesLog->findAll($id, "id_users");
            $globaleAllowedTemp = 0;
            foreach ($pakagesByUsers as $value2) {
                $globaleAllowedTemp += $packages[$value2->getIdPackages()]->getDuration() * 60 * 60;
            }
            $globaleTemp = 0;
            foreach ($value as $date => $heures) {
                if (count($heures) % 2) {
                    $userHours[$id][$date]["error"] = "defaut de pointage";
                } else {
                    $temps = 0;
                    for ($i = 0; $i < count($heures); $i = $i + 2) {
                        [$h, $m, $s] = explode(":", $heures[$i]);
                        $secondes = (int)$h * 60 * 60 + (int)$m * 60 + (int)$s;
                        [$h, $m, $s] = explode(":", $heures[$i + 1]);
                        $secondes2 = (int)$h * 60 * 60 + (int)$m * 60 + (int)$s;
                        $temps += $secondes - $secondes2;
                    }
                    $globaleTemp += $temps;
                }
            }
            $userHours[$id]["globaleAllowedTemp"] = $globaleAllowedTemp;
            $userHours[$id]["globaleTemp"] = $globaleTemp;
            $userHours[$id]["à jour"] = $globaleAllowedTemp > $globaleTemp;
        }
        dd($userHours);
    }

    public function updateAdhesion()
    {
        $packageOne = $this->invocesLines->findAll(31, "id_products");
        $packageTwo = $this->invocesLines->findAll(32, "id_products");
        $packageAll = array_merge($packageOne, $packageTwo);
        $userOK = [];
        $roleNok = [1, 6];
        foreach ($packageAll as $package) {
            $userOK[] = $this->invoces->find($package->getIdInvoces())->getIdUsers();
        }
        $users = $this->users->all();
        \dump($userOK);
        foreach ($users as $user) {
            if (!in_array($user->getId(), $userOK)) {
                if (!in_array(end($this->rolesLog->findAll($user->getId(), "id_users", true, "created_at"))->getIdRoles(), $roleNok)) {
                    $this->rolesLog->updateRole($user->getId(), 2);
                }
            } else {
                if (!in_array(end($this->rolesLog->findAll($user->getId(), "id_users", true, "created_at"))->getIdRoles(), [3, 4, 5, 6])) {
                    $this->rolesLog->updateRole($user->getId(), 3);
                }
            }
        }
        dump("ok");
    }

    public function updateAffichage()
    {
        $roleok = [3, 4, 5, 6];
        $users = $this->users->all();
        foreach ($users as $user) {

            if (in_array(end($this->rolesLog->findAll($user->getId(), "id_users", true, "created_at"))->getIdRoles(), $roleok)) {
                $this->users->update($user->getId(), 'id', ["display" => "0001"]);
            } else {
                $this->users->update($user->getId(), 'id', ["display" => "0000"]);
            }
        }
        dump("ok");
    }
}
