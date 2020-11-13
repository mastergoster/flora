<?php


namespace Core\Controller;

use App\Model\Entity\UsersEntity;

class SecurityController extends controller
{
    public function __construct()
    {

        $this->loadModel("users");
        $this->loadModel("roles");
        $this->loadModel("rolesLog");
    }

    public function isAdmin(): bool
    {
        return $this->accessRole("administrateur");
    }

    public function accessRole($level): bool
    {

        if (!$this->session()->has('users')) {
            return false;
        }

        $user = $this->session()->get('users');
        if (!$user->getActivate()) {
            if ($this->session()->has('validate')) {
                if ($this->session()->get('validate') == $user->getToken()) {
                    $this->users->update($user->getId(), "id", ["activate" => 1]);
                    $this->session()->remove('validate');
                    $this->session()->set('users', $this->users->find($user->getId()));
                    return $this->redirect("userProfile");
                }
            }
            return $this->redirect("activatePage");
        }
        if (\is_string($level)) {
            $role = $this->roles->find($level, 'name');
            $level = $role ? $role->getLevel() : 0;
        }
        $levelsUser = $this->rolesLog->findAll(
            $this->session()->get("users")->getId(),
            "id_users",
            "desc",
            "created_at"
        );

        $levelUser = $this->roles->find(
            $levelsUser[0]->getIdRoles()
        )->getLevel();

        if ($level <= $levelUser) {
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        $this->session()->remove('users');
    }

    /**
     * function de connexion via le mdp ou le code pin
     * @param string $mail 
     * @param string $password 
     * @param bool $pin 
     * @return bool 
     */
    public function login(string $mail, string $password, $pin = false): bool
    {
        $user = $this->users->find($mail, "email");
        if ($user) {
            if (
                \password_verify($password, $user->getPassword()) ||
                ($pin && $user->getPin() == $password)
            ) {
                $this->session()->set("users", $user);
                return true;
            }
        }
        return false;
    }

    /**
     * verification si l'utilisateur est connecté
     * @return bool 
     */
    public function isConnect(): bool
    {
        if ($this->session()->has('users')) {
            return true;
        }
        return false;
    }

    public function updatePassword($password): bool
    {
        if (!$this->session()->has('users')) {
            return false;
        }
        $user = $this->session()->get('users');
        $passwordh = password_hash($password, PASSWORD_BCRYPT);
        return $this->users->update($user->getId(), "id", ["password" => $passwordh]);;
    }

    public function userHydrateSession()
    {
        $user = $this->users->find($this->session()->get("users")->getId(), 'id');
        $levelsUser = $this->rolesLog->findAll(
            $user->getId(),
            "id_users",
            "desc",
            "created_at"
        );

        $levelUser = $this->roles->find(
            $levelsUser[0]->getIdRoles()
        )->getLevel();
        $this->session()->set("users", $user);
        $this->session()->get("users")->level = $levelUser;
    }
}
