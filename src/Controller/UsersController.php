<?php

namespace App\Controller;

use App\Model\Table\UsersTable;
use \Core\Controller\Controller;
use \App\Model\Entity\UserEntity;
use App\Model\Entity\UsersEntity;
use Core\Controller\SmsController;
use Core\Controller\FormController;
use Core\Controller\EmailController;
use App\Model\Entity\RecapConsoEntity;
use Core\Controller\SecurityController;
use Core\Controller\Helpers\TableauController;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->loadModel('users');
        $this->loadModel('packages');
        $this->loadModel('packagesLog');
        $this->loadModel('hours');
        $this->loadModel('roles');
        $this->loadModel('rolesLog');
        $this->loadModel('messages');
        $this->loadModel('invoces');
        $this->loadModel('invocesLines');
    }

    public function login()
    {
        if ($this->security()->isConnect()) {
            $this->redirect('userProfile');
        }

        $form = new FormController();
        $form->field('mail', ["require", "mail"]);
        $form->field('password', ["require", "length" => 7]);


        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            //verifie qu'il n'y ai pas d'erreurs
            if (!$errors) {
                if ($this->security()->login($datas["mail"], $datas["password"])) {
                    if ($this->session()->has("redirect")) {
                        $url = $this->session()->get("redirect");
                        $this->session()->remove("redirect");
                        $this->redirect($url);
                        exit();
                    }
                    $this->redirect("userProfile");
                }
                $this->messageFlash()->error("mdp ou user invalide");
            }
        }

        //supprime le mdp pour le renvoie a la vue
        unset($datas["password"]);

        if ($errors["post"]) {
            unset($errors);
        }
        return $this->render('user/login', [
            'page' => 'Connexion',
            "errors" => $errors
        ]);
    }

    public function logout(): void
    {
        $this->security()->logout();
        $this->redirect("home");
    }

    public function subscribe()
    {
        //Création d'un tableau regroupant les champs requis
        $form = new \Core\Controller\FormController();
        $form->field('email', ["require", "mail"]);
        $form->field('password', ["require", "verify", "length" => 7]);
        $form->field('last_name', ["require"]);
        $form->field('first_name', ["require"]);
        $form->field('phone_number', ["require", "tel"]);

        $errors = $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            //verifie qu'il n'y a pas d'erreur
            if (!$errors) {

                /** @var UsersTable $this->users */
                $userTable = $this->users;

                //verifier que l'adresse mail et/ou que le numéro de téléphone n'existe(nt) pas
                if ($userTable->find($datas["email"], "email")) {
                    $this->messageFlash()->error("Cet email existe déjà, merci de vous connecter");
                    $this->redirect("usersLogin");
                }
                if ($userTable->find($datas["phone_number"], "phone_number")) {
                    $this->messageFlash()->error("Ce numero de téléphone existe déjà, merci de vous connecter");
                    $this->redirect("usersLogin");
                }

                //crypter le password
                $datas["password"] = password_hash($datas["password"], PASSWORD_BCRYPT);
                
                //créer token et pin
                $datas["token"] =  substr(md5(uniqid()), 0, 10);
                $datas["pin"] = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

                //persiter user en bdd
                if (!$userTable->create($datas)) {
                    //formater erreur comme il faut
                    throw new \Exception('erreur de sauvegarde');
                }
                if (!$this->rolesLog->create(["id_roles" => 2, "id_users" => $userTable->lastInsertId()])) {
                    //formater erreur comme il faut
                    throw new \Exception('erreur de sauvegarde');
                }

                //informer que l'enregistrement c'est bien passé
                $this->messageFlash()->success('Enregistrement réussi');

                //envoyer le mail de confirmation avec le token
                $mail = new EmailController();
                $datas["token"] = "http://" . $_SERVER["HTTP_HOST"] . "/validation/" . $datas["token"];
                $mail->object('Validez votre inscription sur le site ' . getenv('stieName'))
                    ->to($datas['email'])
                    ->message('confirmation', compact('datas'))
                    ->send();

                //envoyer le sms
                $sms = new SmsController();
                $sms->numero($datas['phone_number'])
                    ->send(
                        'Pour valider votre code pin : ' .  $datas["pin"]
                            . ', merci de vous connecter via la borne tactile.'
                    );

                //informer le client qu'il va devoir valider son adresse mail
                $this->messageFlash()->success("Verifiez votre boite mail {$datas['mail']} ;)");
                $this->messageFlash()->success("Nous vous avons envoyé un sms sur le numéro {$datas['tel']}");

                //redirection pour se connecter
                header('location: ' . $this->generateUrl('usersLogin'));
                exit();
            }
        }

        //supprime le mdp pour le renvoie a la vue
        unset($datas["password"]);

        if ($errors["post"]) {
            unset($errors);
        }
        return $this->render('user/subscribe', ["datas" => $datas, "errors" => $errors]);
    }


    /**
     * function permettant d'effectuer une demande de changement de mot de passe en cas d'oubli de celui-ci.
     * Un mail est envoyé à l'adresse indiquée si celle-ci existe dans la BDD
     * et si elle a été activée après l'inscription.
     * Ce mail contient un lien, avec le token de l'utilisateur, de la page permttant le changement de mot de passe.
     * Un sms est envoyé à l'utilisateur pour l'informer de la demande de changement de mot de passe.
     *
     * @return void
     */
    public function mdpoublie()
    {
        // Création d'un tableau regroupant les champs requis
        $form = new FormController();
        $form->field('mailmdpoublie', ["require", "mail"]);

        $errors = $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            // Verifie qu'il n'y a pas d'erreur
            if (!$errors) {

                /** @var UsersTable $this->users */
                $userTable = $this->users;
                $message = $this->messageFlash()->success("Un mail vous a été envoyé à l'adresse indiquée " .
                "si celle-ci est valide et a été activée.");

                // Verifie que l'adresse mail existe
                if (!$userTable->find($datas["mailmdpoublie"], "email")) {
                    $message;
                    $this->redirect("usersLogin");
                }

                if ($userTest = $userTable->find($datas["mailmdpoublie"], "email")) {
                    // Récupération de information du compte
                    $datas["phoneNumber"] = $userTest->getPhoneNumber();
                    $datas["activate"] = $userTest->getActivate();
                    $datas["token"] = $userTest->getToken();

                    // Vérifie si mail a été activé
                    if (!$datas["activate"]) {
                        $message;
                        $this->redirect("usersLogin");
                    }

                    // Envoi du mail de confirmation
                    $mail = new EmailController();
                    $datas["token"] = "http://" . $_SERVER["HTTP_HOST"] . "/mdpchange/" . $datas["token"];
                    $mail->object(getenv('stieName') . ' - Votre demande de changement de mot de passe')
                        ->to($datas['mailmdpoublie'])
                        ->message('oublimdp', compact('datas'))
                        ->send();

                    // Informe de l'envoi du mail
                    $message;
                    
                    // Envoi d'un sms d'information
                    $sms = new SmsController();
                    $sms->numero($datas["phoneNumber"])
                        ->send(
                            'Bonjour, vous avez demandé le changement du mot de passe de connexion ' .
                            'à votre espace membre de l\'espace Coworking de MOULINS. ' .
                            'Un mail vous a été envoyé à l\'adresse : ' .  $datas['mailmdpoublie'] .
                            '.L\'espace de Coworking By CoWorkInMoulins.'
                        );

                    // Redirection pour se connecter
                    header('location: ' . $this->generateUrl('usersLogin'));
                    exit();
                }
            }
        }

        if ($errors["post"]) {
            unset($errors);
        }

        // Pour afficher la view en l'état
        return $this->render('user/mdpoublie', [
            "page" => 'MDPOublie',
            "errors" => $errors
        ]);
    }

    /**
     * function permettant le changement de mot de passe dans la BDD.
     * Elle vérifie que le token est conforme ainsi que le code PIN qui est demandé en plus
     * du nouveau mot de passe et de sa confirmation.
     * La paramètre $slug fait référence au token présent dans l'url.
     *
     * @param string $slug
     * @return void
     */
    public function mdpchange(string $slug)
    {
        $user = $this->users->find($slug, "token");

        if (!$user || is_null($slug)) {
            $this->messageFlash()->error("Merci de vous connecter.");
            $this->redirect("usersLogin");
        }

        if ($user && !is_null($slug)) {
            // Création d'un tableau regroupant les champs requis
            $form = new FormController();
            $form->field('password_new', ["require", "verify", "length" => 7]);
            $form->field('pinmdpchange', ["require"]);

            $errors = $form->hasErrors();
            if ($errors["post"] != ["no-data"]) {
                $datas = $form->getDatas();
                // Verifie qu'il n'y a pas d'erreur
                if (!$errors) {
                    // Vérifie si le code PIN saisi est identique à celui de la BDD
                    if ($user->getPin() != $datas["pinmdpchange"]) {
                        $this->messageFlash()->error("Votre demande ne peut aboutir, veuillez réessayer.");
                        $this->redirect("usersMdpchange", ['slug' => $slug]);
                    }

                    // Vérifie si l'utilisatuer à une session en cours même sur un autre poste
                    if ($this->session()->has('users')) {
                        $this->messageFlash()->error("Votre demande ne peut aboutir, veuillez réessayer.");
                        $this->redirect("usersMdpchange", ['slug' => $slug]);
                    }

                    // Insertion dans la BBD du nouveau mot de passe
                    $new_password = password_hash($datas["password_new"], PASSWORD_BCRYPT);
                    $this->users->update($user->getId(), "id", ["password" => $new_password]);
                    ;
                    
                    $this->messageFlash()->success("Votre mot de passe a été modifié avec succès. " .
                    "Vous pouvez vous connecter.");
                    $this->redirect("usersLogin");
                }
            }
        }

        if ($errors["post"]) {
            unset($errors);
        }

        // Pour afficher la view en l'état
        return $this->render('user/mdpchange', [
        "page" => 'Changer le mot de passe',
        "errors" => $errors
        ]);
    }


    public function profile($message = null)
    {
        if (!$this->security()->accessRole('adherants')) {
            $this->redirect('/403');
        }
        $user = $this->session()->get("users");
        $forfaitsLog = new RecapConsoEntity($user->getId());

        $roles = TableauController::assocId($this->roles->all());
        $rolesLog = $this->rolesLog->findAll($user->getId(), "id_users", "DESC", "created_at");
        $roleUser = $roles[reset($rolesLog)->getIdRoles()];



        return $this->render('user/profile', [
            'page' => 'Mon profil',
            'message' => $message,
            'user' => $user,
            'roleUser' => $roleUser,
            'admin' => $this->security()->isAdmin(),
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
            $user = $this->users->getUserByid($id);
            $_SESSION['user'] = $user;

            //Appel de la methode profile de ce controller pour redirection
            return $this->profile('Votre profil a bien été mis à jour');
        }
    }

    public function changePassword()
    {
        if (count($_POST) > 0) {
            $user = $this->users->getUserById(htmlspecialchars($_POST['id']));
            //Vérification de l'ancien mot de passe mots de passes
            if (password_verify(htmlspecialchars($_POST['old_password']), $user->getPassword())) {
                //Vérification correspondance des mots de passe
                if (htmlspecialchars($_POST['password']) == htmlspecialchars($_POST['veriftyPassword'])) {
                    //Hashage du password
                    $password = password_hash(htmlspecialchars(htmlspecialchars($_POST['password'])), PASSWORD_BCRYPT);

                    //Mise à jour de la bdd grace à methode update de /core/Table.php
                    if ($this->users->update($_POST['id'], 'id', ['password' => $password])) {
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
        if ($this->session()->get("users")->getId() != $_POST["id_user"]) {
            $this->jsonResponse403();
        }
        header('content-type:application/json');

        echo json_encode($this->hours->ajout($this->session()->get("users")->getId()));
    }

    public function validate(string $verify)
    {
        $token = $this->users->findAll($verify, "token");

        if ($token && !is_null($verify)) {
            $this->session()->set("validate", $verify);
        }
        if ($this->session()->has("users")) {
            $this->redirect("userProfile");
        }
        $this->messageFlash()->error("Merci de vous connecter");
        $this->redirect("usersLogin");
    }

    private function isValidate(UsersEntity $user)
    {
        if (isset($_SESSION["validate"]) && $user->getToken() == $_SESSION["validate"]) {
            $this->users->update($user->getId(), 'id', ["activate" => '1', "token" => ""]);
            $user->setToken("");
            $user->setActivate("1");
            unset($_SESSION["validate"]);
        }
        if ($user->getActivate() == '0' && $user->getVerify() == '0') {
            $this->messageFlash()->error("Merci de valider votre compte (un mail vous a été envoyé)");
            $_SESSION["user"] = "";
            header('location: /login');
            exit();
        }
    }

    public function activatePage()
    {
        if (!$this->session()->has("users")) {
            $this->redirect("usersLogin");
        }
        $user = $this->session()->get("users");
        if ($user->getActivate()) {
            $this->redirect("userProfile");
        }
        return $this->render(
            "user/activate",
            [
                "user" => $user
            ]
        );
    }

    public function mail()
    {
        $form = new FormController();
        $form->field("name", ["require"]);
        $form->field("email", ["require", "mail"]);
        $form->field("message", ["require"]);
        $errors =  $form->hasErrors();
        if ($errors["post"] != ["no-data"]) {
            $datas = $form->getDatas();
            if (!$errors) {
                $this->messages->create($datas);
                $errors["error"] = false;
            } else {
                $errors["error"] = true;
            }
        }
        return $this->jsonResponse($errors);
    }

    public function invoces()
    {
        if (!$this->session()->has("users")) {
            $this->redirect("usersLogin");
        }
        $user = $this->session()->get("users");

        $invoce = $this->invoces->allActivateByUser($user->getId());
        return $this->render(
            "user/invoces",
            [
                "invoces" => $invoce
            ]
        );
    }
    public function invoce($id)
    {
        if (!$this->session()->has("users")) {
            $this->redirect("usersLogin");
        }
        $user = $this->session()->get("users");
        $invoce = $this->invoces->findActivate($id, "id");
        if (!$invoce || $user->getId() != $invoce->getIdUsers()) {
            $this->messageFlash()->error("action non permise");
            $this->redirect("userInvoces");
        }
        return $this->renderPdf("user/invoce", ["invoce" => $invoce, "user" => $user, "title" => $invoce->getRef()]);
    }

    public function edit()
    {

        if (!$this->session()->has("users")) {
            $this->redirect("usersLogin");
        }
        $user = $this->session()->get("users");


        $formUpdate = new FormController();
        $formUpdate->field("first_name", ["require"]);
        $formUpdate->field("last_name", ["require"]);
        $formUpdate->field("street", ["require"]);
        $formUpdate->field("city", ["require"]);
        $formUpdate->field("postal_code", ["require"]);
        $formUpdate->field("desc", ["require"]);
        if ($user->getVerify() == "1") {
            $formUpdate->field("pin", ["ExactLength" => 4, "int"]);
        }
        $errors = [];
        if ($this->request()->query->has("user")) {
            $errors =  $formUpdate->hasErrors();
            if ($errors["post"] != ["no-data"]) {
                $datas = $formUpdate->getDatas();
                if (!$errors) {
                    $this->users->update($user->getId(), "id", $datas);
                }
            }
        }

        $formPassword = new FormController();
        $formPassword->field("id", ["require"]);
        $formPassword->field("password", ["require", "length" => 7]);
        $errorsPassword = [];
        $formPassword->field("password_new", ["require", "verify"]);
        if ($this->request()->query->has("password")) {
            $errorsPassword =  $formPassword->hasErrors();
            if ($errorsPassword["post"] != ["no-data"]) {
                $datasPassword = $formPassword->getDatas();
                if (!$errorsPassword) {
                    if ($user->getId() == $datasPassword["id"] &&
                        $this->security()->login($user->getEmail(), $datasPassword["password"])
                    ) {
                        if ($this->security()->updatePassword($datasPassword["password_new"])) {
                            $this->messageFlash()->success("Le mot de passe a bien été changé");
                        } else {
                            $this->messageFlash()->error("erreur inatendu lol");
                        }
                    } else {
                        $this->messageFlash()->error("mot de passe invalide");
                    }
                }
            }
        }
        $user = $this->users->find($user->getId(), 'id');
        $this->session()->set("users", $user);
        foreach ($errors as $error) {
            $this->messageFlash()->error($error[0]);
        }
        foreach ($errorsPassword as $error) {
            $this->messageFlash()->error($error[0]);
        }
        return $this->render("user/edit", ["user" => $user, "errors" => $errors, "errorsP" => $errorsPassword]);
    }
}
