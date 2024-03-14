<?php

namespace App\Model\Table;

use Core\Model\Table;

class UsersTable extends Table
{
    public function alldisplay(string $display, bool $order = false, $colomun = "id")
    {
        if ($order) {
            $order = " ORDER BY  UPPER(" . $colomun . ") " . ($order === true ? "ASC" : "DESC");
        } else {
            $order = "";
        }
        return $this->query("SELECT * FROM $this->table WHERE display = ?" . $order, [$display]);
    }

    public function findAllByRole($id_role)
    {
        return $this->query(
            "SELECT $this->table.*
            FROM $this->table 
            Left join roles_log on $this->table.id = roles_log.id_users 
            WHERE roles_log.id_roles = ? AND roles_log.updated_at is null", 
            [$id_role]
            );

    }
    // public function getUser($mail, $password)
    // {
    //     $user = $this->query("SELECT $this->table.*,
    //             role_liaison.id_role AS role_id,
    //             role_liaison.created_at AS role_created_at,
    //             role.nom AS role_nom,
    //             role.activate AS role_activate
    //         FROM $this->table
    //         LEFT JOIN role_liaison ON $this->table.id = role_liaison.id_user
    //         LEFT JOIN role ON role.id = role_liaison.id_role
    //         WHERE mail = ?", [$mail], true);
    //     if ($user) {
    //         if (password_verify($password, $user->getPassword())) {
    //             $user->setPassword('');
    //             return $user;
    //         }
    //     }
    //     return false;
    // }

    // public function all($o = false, $c = "id")
    // {
    //     $user = $this->query("SELECT $this->table.*,
    //             role_liaison.id_role AS role_id,
    //             role_liaison.created_at AS role_created_at,
    //             role.nom AS role_nom,
    //             role.activate AS role_activate
    //         FROM $this->table
    //         LEFT JOIN role_liaison ON $this->table.id = role_liaison.id_user
    //         LEFT JOIN role ON role.id = role_liaison.id_role");
    //     return $user;
    // }

    // public function getUserByid($id)
    // {
    //     return $this->query("SELECT $this->table.*,
    //     role_liaison.id_role AS role_id,
    //     role_liaison.created_at AS role_created_at,
    //     role.nom AS role_nom,
    //     role.activate AS role_activate
    //     FROM $this->table
    //     LEFT JOIN role_liaison ON $this->table.id = role_liaison.id_user
    //     LEFT JOIN role ON role.id = role_liaison.id_role
    //     WHERE $this->table.id = ?", [$id], true);
    // }


    // public function newUser($fields)
    // {
    //     $this->query("INSERT INTO user
    //     (nom, prenom, mail, password, pin, token, tel, activate, verify)
    //     VALUES
    //     (:name, :lastname, :mail, :password, :pin, :token, :tel,'0','0')", $fields);
    // }

    // public function connect($mail, $password)
    // {
    //     $user = $this->find($mail, "mail");
    //     if (\password_verify($password, $user->getPassword())) {
    //         return $user;
    //     } elseif ($password == $user->getPin()) {
    //         return $user;
    //     }
    //     return false;
    // }

    // public function verify($id): void
    // {
    //     $data = ["verify" => "1"];
    //     $this->update($id, "id", $data);
    // }
}
