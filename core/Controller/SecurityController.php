<?php

namespace Core\Controller;

use App\Model\Table\RolesLogTable;
use App\Model\Table\RolesTable;
use App\Model\Table\UsersTable;
use Core\Controller\Database\DatabaseController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityController
{
    public function __construct(DatabaseController $db, SessionInterface $session)
    {
        $this->users = new UsersTable($db);
        $this->roles = new RolesTable($db);
        $this->rolesLog = new RolesLogTable($db);
        $this->session = $session;
    }

    public function isAdmin(): bool
    {
        return $this->accessRole("administrateur");
    }

    public function accessRole($level): bool
    {

        if (!$this->session->has('users')) {
            return false;
        }

        $user = $this->users->find($this->session->get('users')->getId());
        if (!$user->getActivate()) {
            return false;
        }
        if (\is_string($level)) {
            $role = $this->roles->find($level, 'name');
            $level = $role ? $role->getLevel() : 60;
        }
        $levelsUser = $this->rolesLog->findAll(
            $this->session->get("users")->getId(),
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
        $this->session->remove('users');
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
            if (password_verify($password, $user->getPassword())) {
                $this->session->set("users", $user);
                return true;
            } elseif ($pin && $user->getPin() == $password) {
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
        if ($this->session->has('users')) {
            return true;
        }
        return false;
    }

    public function updatePassword($password): bool
    {
        if (!$this->session->has('users')) {
            return false;
        }
        $user = $this->session->get('users');
        $passwordh = password_hash($password, PASSWORD_BCRYPT);
        return $this->users->update($user->getId(), "id", ["password" => $passwordh]);
    }

    public function userHydrateSession()
    {
        $user = $this->users->find($this->session->get("users")->getId(), 'id');
        $levelsUser = $this->rolesLog->findAll(
            $user->getId(),
            "id_users",
            "desc",
            "created_at"
        );
        $levelUser = $this->roles->find(
            $levelsUser[0]->getIdRoles()
        )->getLevel();
        $this->session->set("users", $user);
        $this->session->get("users")->level = $levelUser;
    }

    public function isActivate()
    {
        if (!$this->session->has('users')) {
            return false;
        }
        $user = $this->users->find($this->session->get('users')->getId());
        if (!$user->getActivate()) {
            if ($this->session->has('validate')) {
                if ($this->session->get('validate') == $user->getToken()) {
                    $this->users->update($user->getId(), "id", ["activate" => 1]);
                    $this->session->remove('validate');
                    $this->session->set('users', $this->users->find($user->getId()));
                }
            }
            return false;
        }
        return true;
    }
}
