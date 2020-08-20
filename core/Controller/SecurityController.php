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

    public function isConnect(): bool
    {
        if ($this->session()->has('users')) {
            return true;
        }
        return false;
    }
}