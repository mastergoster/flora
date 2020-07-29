<?php

namespace App\Controller;

use App\Model\Table\UserTable;
use \Core\Controller\Controller;
use \App\Model\Entity\UserEntity;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\EmailController;
use \App\Model\Entity\UserInfosEntity;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->loadModel('user');
        $this->loadModel('forfait');
        $this->loadModel('forfaitLog');
        $this->loadModel('heures');
    }

    public function login()
    {
        if ($_SESSION['user']) {
            header('location: /user/profile');
            exit();
        }
        $message = false;
        if (count($_POST) > 1) {
            $password = htmlspecialchars($_POST['password']);
            $user = $this->user->getUser(htmlspecialchars($_POST['mail']), $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header('location: /user/profile');
                exit();
            } else {
                $message = "Adresse mail ou mot de passe incorrect";
            }
        }

        return $this->render('user/login', [
            'page' => 'Connexion',
            'message' => $message
        ]);
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        header('location: /');
        exit();
    }

    public function subscribe()
    {

        //Création d'un tableau regroupant les champs requis
        $form = new \Core\Controller\FormController();
        $form->field('mail', ["require", "verify"]);
        $form->field('password', ["require", "verify", "length" => 4]);


        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();

            //verifie qu'il n'y ai pas d'erreurs
            if (!$errors) {

                /** @var UserTable $this->user */
                $userTable = $this->user;

                //verifier que l'adresse mail n'existe pas
                if ($userTable->find($datas["mail"], "mail")) {
                    return "crée message comme quoi le mail existe déjà";
                }

                //crypter password
                $datas["password"] = password_hash($datas["password"], PASSWORD_BCRYPT);
                //cree token
                $datas["token"] = substr(md5(uniqid()), 0, 10);
                //persiter user en bdd
                if ($userTable->newUser($datas)) {
                    //formater erreure comme il faut
                    throw new \Exception('erreur de sauvegarde');
                }
                //informer que l'enregistrement c'est bien passé
                $this->messageFlash()->success('Enregistrement reussi');
                //evoyer mail de confirmation avec le token
                $mail = new EmailController();
                $mail->object('valiez votre inscription sur le site labiere.fr')
                    ->to($datas['mail'])
                    ->message('confirmation', compact('datas'))
                    ->send();

                //informer le client qu'il var devoir valier son adresse mail
                $this->messageFlash()->success("Verifiez votre bote mail {$datas['mail']} ;)");
                //redirection pour se connecter
                header('location: ' . $this->generateUrl('usersLogin'));
                exit();
            }
        }
        //supprime le mdp pour le renvoie a la vue
        unset($datas["password"]);
        unset($errors["post"]);

        return $this->render('user/subscribe', ["datas" => $datas, "errors" => $errors]);
    }




    public function profile($message = null)
    {
        if (null !== $_SESSION['user'] && $_SESSION['user']) {
            $file = 'profile';
            $page = 'Mon profil';
        } else {
            header('location: /login');
            exit();
        }
        $user = $_SESSION['user'];
        $forfaitsLog = (new RecapConsoEntity($user->getId()));



        return $this->render('user/profile', [
            'page' => 'Mon profil',
            'message' => $message,
            'user' => $user,
            'forfait' => $forfaitsLog->GetHeurDispo(),
            'ForfaitExpiredAt' => $forfaitsLog->getLastForfaitExpiredAt(),
            'last' => $forfaitsLog->getLastDay(),
            'today' => $forfaitsLog->getToDay(),
            'presence' => $forfaitsLog->getPresence()
        ]);
    }




    public function updateUser()
    {

        if (count($_POST) > 0) {
            $id = (int) array_pop($_POST); //Stockage de la dernière case de $_POST dans $id
            //Mise à jours bdd grace à methode update de /core/Table.php
            $bool = $this->UserInfos->update($id, 'user_id', $_POST);
            //Mise à jours de la SESSION['user']
            $user = $this->user->getUserByid($id);
            $_SESSION['user'] = $user;

            //Appel de la methode profile de ce controller pour redirection
            return $this->profile('Votre profil a bien été mis à jour');
        }
    }

    public function changePassword()
    {
        if (count($_POST) > 0) {
            $user = $this->user->getUserById(htmlspecialchars($_POST['id']));
            //Vérification de l'ancien mot de passe mots de passes
            if (password_verify(htmlspecialchars($_POST['old_password']), $user->getPassword())) {
                //Vérification correspondance des mots de passe
                if (htmlspecialchars($_POST['password']) == htmlspecialchars($_POST['veriftyPassword'])) {
                    //Hashage du password
                    $password = password_hash(htmlspecialchars(htmlspecialchars($_POST['password'])), PASSWORD_BCRYPT);

                    //Mise à jour de la bdd grace à methode update de /core/Table.php
                    if ($this->user->update($_POST['id'], 'id', ['password' => $password])) {
                        $message = 'Votre mot de passe a bien été modifié';
                    } else {
                        $message = 'Une erreur s\'est produite';
                    }
                } else {
                    $message = 'Les mots de passes ne correspondent pas';
                }
            } else {
                $message = 'Mot de passe erroné';
            }
            return $this->profile($message); //Appel de la methode profile de ce controller pour redirection
        }
    }

    public function ajaxNewUserLine()
    {
        if ($_SESSION["user"]->getId() != $_POST["id_user"]) {
            $this->jsonResponse403();
        }
        header('content-type:application/json');

        echo json_encode($this->heures->ajout($_SESSION["user"]->getId()));
    }
}
