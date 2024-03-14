<?php

namespace App\Controller;

use App\Model\Table\UsersTable;
use \Core\Controller\Controller;
use App\Services\InvocesServices;
use Core\Controller\SmsController;
use Core\Controller\FormController;
use App\Model\Entity\RolesLogEntity;
use Core\Controller\EmailController;
use App\Model\Entity\RecapConsoEntity;
use Symfony\Component\Console\Helper\Helper;
use Core\Controller\Helpers\TableauController;
use Symfony\Component\HttpFoundation\Response;

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
        $this->loadModel('images');
        $this->loadModel('products');
        $this->loadModel('ressources');
        $this->loadModel('books');
    }

    public function login(): Response
    {
        if ($this->security()->isConnect()) {
            return $this->redirect('userProfile');
        }

        $form = new FormController();
        $form->field('mail', ["require", "mail"]);
        $form->field('password', ["require", "length" => 7]);


        $errors =  $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            //verifie qu'il n'y ai pas d'erreurs
            if (!$errors) {
                if ($this->security()->login($datas["mail"], $datas["password"])) {
                    if ($this->session()->has("redirect")) {
                        $url = $this->session()->get("redirect");
                        $this->session()->remove("redirect");
                        return $this->redirect($url);
                    }
                    return $this->redirect("userProfile");
                }
                $this->messageFlash()->error("Mot de passe ou utilisateur invalide");
            }
        }


        if (isset($datas["password"])) {
            unset($datas["password"]);
        }

        if (isset($errors["post"])) {
            $errors = [];
        }
        return $this->render('user/login', [
            'page' => 'Connexion',
            "errors" => $errors
        ]);
    }

    public function logout(): Response
    {
        $this->security()->logout();
        return $this->redirect("home");
    }

    public function subscribe(): Response
    {
        //Création d'un tableau regroupant les champs requis
        $form = new \Core\Controller\FormController();
        $form->field('email', ["require", "mail"]);
        $form->field('password', ["require", "verify", "length" => 7]);
        $form->field('last_name', ["require"]);
        $form->field('first_name', ["require"]);
        $form->field('phone_number', ["require", "tel"]);

        $errors = $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            //verifie qu'il n'y a pas d'erreur
            if (!$errors) {

                /** @var UsersTable $this->users */
                $userTable = $this->users;

                //verifier que l'adresse mail et/ou que le numéro de téléphone n'existe(nt) pas
                if ($userTable->find($datas["email"], "email")) {
                    $this->messageFlash()->error("Cet email existe déjà, merci de vous connecter");
                    return $this->redirect("usersLogin");
                }
                if ($userTable->find($datas["phone_number"], "phone_number")) {
                    $this->messageFlash()->error("Ce numero de téléphone existe déjà, merci de vous connecter");
                    return $this->redirect("usersLogin");
                }

                //crypter le password
                $password_private = $datas["password"];
                $datas["password"] = password_hash($datas["password"], PASSWORD_BCRYPT);

                //créer token et pin
                $datas["token"] =  substr(md5(uniqid()), 0, 10);
                $datas["pin"] = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
                $datas["display"] = "0000";

                //persiter user en bdd
                if (!$userTable->create($datas)) {
                    //formater erreur comme il faut
                    throw new \Exception('erreur de sauvegarde');
                }
                $roleAttente = $this->roles->find("attente", 'name');
                if (!$this->rolesLog->create(["id_roles" => $roleAttente->getID(), "id_users" => $userTable->lastInsertId()])) {
                    //formater erreur comme il faut
                    throw new \Exception('erreur de sauvegarde');
                }

                //informer que l'enregistrement c'est bien passé
                $this->messageFlash()->success('Votre inscription a été validée.');

                //envoyer le mail de confirmation avec le token
                $mail = new EmailController();
                $datas["token"] = "http://" . $_SERVER["HTTP_HOST"] . "/validation/" . $datas["token"];
                $mail->object('Validez votre inscription sur le site ' . getenv('siteName'))
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
                $this->messageFlash()->success("Nous vous avons envoyé un sms sur le numéro " .
                    "{$datas['phone_number']} et un mail à l'adresse {$datas['email']}.");

                // Login automatique après inscription valide
                $this->security()->login($datas["email"], $password_private);
                if ($this->session()->has("redirect")) {
                    $url = $this->session()->get("redirect");
                    $this->session()->remove("redirect");
                    return $this->redirect($url);
                }
                return $this->redirect("userProfile");
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
    public function mdpoublie(): Response
    {
        // Création d'un tableau regroupant les champs requis
        $form = new FormController();
        $form->field('mailmdpoublie', ["require", "mail"]);

        $errors = $form->hasErrors();
        if (!isset($errors["post"])) {
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
                    return $this->redirect("usersLogin");
                }

                if ($userTest = $userTable->find($datas["mailmdpoublie"], "email")) {
                    // Récupération de information du compte
                    $datas["phoneNumber"] = $userTest->getPhoneNumber();
                    $datas["activate"] = $userTest->getActivate();
                    $datas["token"] = $userTest->getToken();

                    // Vérifie si mail a été activé
                    if (!$datas["activate"]) {
                        $message;
                        return $this->redirect("usersLogin");
                    }

                    // Envoi du mail de confirmation
                    $mail = new EmailController();
                    $datas["token"] = "http://" . $_SERVER["HTTP_HOST"] . "/mdpchange/" . $datas["token"];
                    $mail->object(getenv('siteName') . ' - Votre demande de changement de mot de passe')
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
                                '. L\'espace de Coworking By CoWorkInMoulins.'
                        );

                    // Redirection pour se connecter
                    return $this->redirect("usersLogin");
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
    public function mdpchange(string $slug): Response
    {
        $user = $this->users->find($slug, "token");

        if (!$user || is_null($slug)) {
            $this->messageFlash()->error("Merci de vous connecter.");
            return $this->redirect("usersLogin");
        }

        if ($user && !is_null($slug)) {
            // Création d'un tableau regroupant les champs requis
            $form = new FormController();
            $form->field('password_new', ["require", "verify", "length" => 7]);
            $form->field('pinmdpchange', ["require"]);

            $errors = $form->hasErrors();
            if (!isset($errors["post"])) {
                $datas = $form->getDatas();
                // Verifie qu'il n'y a pas d'erreur
                if (!$errors) {
                    // Vérifie si le code PIN saisi est identique à celui de la BDD
                    if ($user->getPin() != $datas["pinmdpchange"]) {
                        $this->messageFlash()->error("Votre demande ne peut aboutir, veuillez réessayer.");
                        return $this->redirect("usersMdpchange", ['slug' => $slug]);
                    }

                    // Vérifie si l'utilisatuer à une session en cours même sur un autre poste
                    if ($this->session()->has('users')) {
                        $this->messageFlash()->error("Votre demande ne peut aboutir, veuillez réessayer.");
                        return $this->redirect("usersMdpchange", ['slug' => $slug]);
                    }

                    // Insertion dans la BBD du nouveau mot de passe
                    $new_password = password_hash($datas["password_new"], PASSWORD_BCRYPT);
                    $this->users->update($user->getId(), "id", ["password" => $new_password]);

                    $this->messageFlash()->success("Votre mot de passe a été modifié avec succès. " .
                        "Vous pouvez vous connecter.");
                    return $this->redirect("usersLogin");
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


    public function profile($message = null): Response
    {
        if (!$this->security()->accessRole(20)) {
            if (!$this->security()->isActivate()) {
                return $this->redirect('activatePage');
            } elseif ($this->security()->isAttente()) {
                return $this->redirect('choiceAdhesion');
            } else {
                return $this->redirect('/403');
            }
        }
        $user = $this->session()->get("users");
        $forfaitsLog = new RecapConsoEntity($user->getId());

        $roles = TableauController::assocId($this->roles->all());
        $rolesLog = $this->rolesLog->findAll($user->getId(), "id_users", "DESC", "created_at");
        /** TODO change dynamic ID */
        $norole = $this->roles->find("attente", 'name');
        $rolesLog = $rolesLog ? $rolesLog : [
            $norole->getId() => (new RolesLogEntity())->setIdRoles($norole->getId())
        ];
        $roleUser = $roles[reset($rolesLog)->getIdRoles()];
        if ($user->getIdImages()) {
            $images = $this->images->find($user->getIdImages());
            $user->img = "/" . $images->getRef() . "/" . $images->getName();
        }


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




    public function updateUser(): Response
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

    public function changePassword(): Response
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

    public function ajaxNewUserLine(): Response
    {
        if ($this->session()->get("users")->getId() != $_POST["id_user"]) {
            $this->jsonResponse403();
        }
        return $this->jsonResponse($this->hours->ajout($this->session()->get("users")->getId()));
    }

    public function validate(string $verify): Response
    {
        $token = $this->users->findAll($verify, "token");
        // Vérifie si le token n'existe pas ou que $verify n'est pas nul
        if (!$token || is_null($verify)) {
            return $this->redirect("home");
        }
        // Si le mail a déjà été activé, renvoi sur la page d'accueil (le mail d'activation n'est plus utilisable)
        if ($token['0']->getActivate() && !is_null($verify)) {
            $this->messageFlash()->error("Cette adresse mail a déjà été validée.");
            return $this->redirect("home");
        }
        // Passe le $verify dans la session['validate'] qui sera utilisé pour la 1ère connexion
        if ($token && !$token['0']->getActivate() && !is_null($verify)) {
            $this->session()->set("validate", $verify);
        }
        if ($this->session()->has("users")) {
            $this->messageFlash()->success("Votre adresse mail a été validée.");
            return $this->redirect("userProfile");
        }
        $this->messageFlash()->error("Merci de vous connecter à votre espace membre pour valider votre adresse mail.");
        return $this->redirect("usersLogin");
    }

    public function activatePage(): Response
    {
        if ($this->request()->query->has("disconnected")) {
            $this->security()->logout();
            return $this->redirect("usersLogin");
        }

        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
        }

        $user = $this->session()->get("users");

        if ($this->request()->query->has("newMail")) {
            $userMail = $user->getEmail();
            $userToken = $user->getToken();

            // Envoi le mail d'activation avec le token
            $mail = new EmailController();
            $datas["token"] = "http://" . $_SERVER["HTTP_HOST"] . "/validation/" . $userToken;
            $mail->object('Validez votre inscription sur le site ' . getenv('siteName'))
                ->to($userMail)
                ->message('confirmation', compact('datas'));
            $mail->send();
            $this->messageFlash()->success("Un nouveau mail d'activation vous a été envoyé.");
            return $this->redirect("usersLogin");
        }

        if ($user->getActivate()) {
            return $this->redirect("usersLogin");
        }

        return $this->render(
            "user/activate",
            [
                "user" => $user
            ]
        );
    }

    public function mail(): Response
    {
        $paramUnique = TableauController::tableObjectToString("email", $this->users->all());

        $form = new FormController();
        $form->field("name", ["require"]);
        $form->field("email", ["require", "mail", "unique" => $paramUnique]);
        $form->field("message", ["require"]);
        $form->field("norobot", ["require", "robot"]);
        $form->field("norobot2", ["notRequire"]);
        $form->field("norobot3", ["require"]);
        $errors = $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();
            $datas['id_roles'] = 5; // 4 = Administrateur
            if (!$errors) {
                unset($datas["norobot"]);
                unset($datas["norobot2"]);
                unset($datas["norobot3"]);
                $this->messages->create($datas);
                $mail = new EmailController();
                $mail->object(getenv('siteName') . ' - Vous avez un nouveau message sur votre espace')
                    ->to("contact@coworkinmoulins.fr")
                    ->message('newMessage', compact('datas'))
                    ->send();
                $errors["error"] = false;
            } else {
                $errors["error"] = true;
                if (isset($errors['email'])) {
                    $errors['email'] = ["Merci d'envoyer vos messages via votre messagerie interne."];
                }
            }
        }
        return $this->jsonResponse($errors);
    }

    public function invoces(): Response
    {
        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
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
    public function invoce($id): Response
    {
        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
        }
        $user = $this->session()->get("users");
        $invoce = $this->invoces->findActivate($id, "id");
        if (!$invoce || $user->getId() != $invoce->getIdUsers()) {
            $this->messageFlash()->error("action non permise");
            return $this->redirect("userInvoces");
        }
        return $this->renderPdf("user/invoce", ["invoce" => $invoce, "user" => $user, "title" => $invoce->getRef()]);
    }

    public function edit(): Response
    {
        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
        }
        $user = $this->session()->get("users");
        $formUpdate = new FormController();
        $formUpdate->field("first_name", ["require"]);
        $formUpdate->field("last_name", ["require"]);
        $formUpdate->field("street", ["require"]);
        $formUpdate->field("city", ["require"]);
        $formUpdate->field("postal_code", ["require"]);
        $formUpdate->field("desc", ["require"]);
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
        }

        $formPassword = new FormController();
        $formPassword->field("id", ["require"]);
        $formPassword->field("password", ["require", "length" => 7]);
        $errorsPassword = [];
        $formPassword->field("password_new", ["require", "verify"]);
        if ($this->request()->query->has("password")) {
            $errorsPassword =  $formPassword->hasErrors();
            if (!isset($errors["post"])) {
                $datasPassword = $formPassword->getDatas();
                if (!$errorsPassword) {
                    if ($user->getId() == $datasPassword["id"] &&
                        $this->security()->login($user->getEmail(), $datasPassword["password"])
                    ) {
                        if ($this->security()->updatePassword($datasPassword["password_new"])) {
                            $this->messageFlash()->success("Le mot de passe a bien été changé.");
                        } else {
                            $this->messageFlash()->error("Erreur inattendu lol");
                        }
                    } else {
                        $this->messageFlash()->error("Mot de passe invalide.");
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
        if ($user->getIdImages()) {
            $images = $this->images->find($user->getIdImages());
            $user->img = "/" . $images->getRef() . "/" . $images->getName();
        }
        return $this->render("user/edit", ["user" => $user, "errors" => $errors, "errorsP" => $errorsPassword]);
    }

    /**
     * Function : qui permet d'afficher les messages émis en interne
     * Affiche soit les messages qui sont destinés à l'user en session (via l'id du user connecté)
     * soit les messages qui sont destinés au groupe (rôle) auquel le user connecté appartient
     * ainsi qu'au groupe de level inférieur
     *
     * @return void
     */
    public function userMessages()
    {
        if (!$this->session()->has("users")) {
            return $this->redirect("usersLogin");
        }
        $display = "d-none";
        // Récupère les messages selon l'id user ou le level de l'user
        $user = $this->session()->get("users");

        $messages = $this->messages->messagesByIdUserAndLevelUser(
            $user->getId(),
            $user->level,
            $user->getEmail()
        );

        foreach ($messages as $key => $value) {
            if ($value->idExp == $user->getId()) {
                if ($value->getIdRoles() != null) {
                    $messages["groupe"]["r-" . $value->getIdRoles()][] = $value;
                } elseif ($value->getIdUsers() != null) {
                    $messages["perso"][$value->getIdUsers()][] = $value;
                }
            } elseif ($value->getIdRoles() != null) {
                $messages["groupe"]["r-" . $value->getIdRoles()][] = $value;
            } elseif ($value->idExp != null) {
                $messages["perso"][$value->idExp][] = $value;
            }
            unset($messages[$key]);
        }

        // Récupère l'id de tous les rôles et le nom associé pour l'affichage des destinataires possible
        $roles = $this->roles->all();

        $dests =  TableauController::assocId($this->users->all(true, "last_name"));

        foreach ($roles as $key => $value) {
            $value->value = "r-" . $value->getId();
            $roles[$value->value] = $value;
            unset($roles[$key]);
        }

        // Envoi du message par l'user à un destinataire

        $form = new FormController();
        $form->field("destinataire", ["require"]);
        $form->field("message", ["require"]);

        $errors = $form->hasErrors();
        if (!isset($errors["post"])) {
            $datas = $form->getDatas();

            if (strpos($datas['destinataire'], "r-") === 0) {
                $datas['id_roles'] = str_replace("r-", "", $datas['destinataire']);
                unset($datas['destinataire']);
            } else {
                $datas['id_users'] = $datas['destinataire'];
                unset($datas['destinataire']);
            }

            $datas['name'] = strtoupper($this->session()->get("users")->getLastName()) .
                " " . ucfirst(
                    $this->session()->get("users")->getFirstName()
                );
            $datas['email'] = $this->session()->get("users")->getEmail();

            if (!$errors) {
                $this->messages->create($datas);
                if(isset($datas['id_users'])){
                    $usersReception = $this->users->findAll($datas['id_users'], "id");
                }
                if(isset($datas['id_roles'])){
                    $usersReception = $this->users->findAllByRole($datas['id_roles']);
                }
                $datas["message"]  = explode("\n", $datas["message"]);
                foreach ($usersReception as $value) {
                    $mail = new EmailController();
                    $mail->object(getenv('siteName') . ' - Vous avez un nouveau message sur votre espace')
                        ->to($value->getEmail())
                        ->message('newMessage', compact('datas'))
                        ->send();
                }
                $nombre = count($usersReception);
                $this->messageFlash()->success("Votre message a bien été envoyé à $nombre personnes.");

                
                unset($datas);
                return $this->redirect("userMessages");
            } else {
                $this->messageFlash()->error("Envoi impossible : " .
                    "Vous devez choisir un destinataire et écrire un message.");
                $display = "";
            }
        }

        if ($errors["post"]) {
            $errors = [];
        }

        // Affiche la vue
        return $this->render(
            "user/messages",
            [

                "user" => $user,
                "roles" => $roles,
                "dests" => $dests,
                "errors" => $errors,
                "items" => $messages,
                "display" => $display,
            ]
        );
    }

    public function adhesion($id = null)
    {
        if (!$this->security()->isAttente()) {
            return $this->redirect('userProfile');
        }
        $variable = [];

        switch ($id) {
            case '1':
                $variable = ["role" => 3, "product" => 31];
                break;
            case '2':
                $variable = ["role" => 3, "product" => 32];
                break;
            case '3':
            case '4':
            case '5':
                break;
            default:
                break;
        }
        if (count($variable) == 2) {
            $user = $this->session()->get("users");
            $this->rolesLog->updateRole($user->getId(), $variable["role"]);
            $invocesServices = new InvocesServices;
            $invoce = $invocesServices->getNewInvoce(["id_user" => $user->getId(), "date_at" => date("Y-m-d H:i:s")]);
            $product = $this->products->findForInvoce($variable["product"]);
            $product->setIdInvoces($invoce->getId());
            $product->setQte(1);
            $product->setDiscount(0);
            $product->setIdProducts($product->getId());
            $product->setId(null);

            $this->invocesLines->create($product, true);
            $invocesServices->activate($invoce);
            $this->messageFlash()->success("Votre demande d'adhésion a bien été prise en compte.");
            return $this->redirect('userInvoces');
        }





        return $this->render(
            "user/adhesion",
            []
        );
    }

    public function resevation()
    {
        $events = [];
        foreach ($this->books->all() as $value) {
            $events[] = $value->__toArray();
        }
        $ressources = $this->ressources->all();
        return $this->render(
            "user/reservation",
            ["events" => \json_encode($events),
            "ressources" => $ressources
            ]
        );
    }
}
