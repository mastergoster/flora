<?php
namespace Core\Controller\Database;

use \PDO;

class DatabaseMysqlController extends DatabaseController
{

    private $pdo;

    public function __construct(
        string $db_name,
        string $db_user = 'root',
        string $db_pass = 'root',
        string $db_host = 'localhost',
        string $db_char = 'UTF8'
    ) {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
        $this->db_char = $db_char;
    }

    public function getPDO()
    {
        if (is_null($this->pdo)) {
            $pdo = new PDO(
                "mysql:host=" . $this->db_host .
                    ";dbname=" . $this->db_name,
                $this->db_user,
                $this->db_pass
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

    public function query(string $statement, ?string $class_name = null, bool $one = false)
    {
        $req = $this->getPDO()->query($statement);
        if (strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $req;
        }
        if (is_null($class_name)) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }
        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }

        return $datas;
    }

    public function prepare(string $statement, array $attributes, ?string $class_name = null, bool $one = false)
    {
        $req = $this->getPDO()->prepare($statement);
        $res = $req->execute($attributes);
        if (strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $res;
        }
        if (is_null($class_name)) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }
        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }

        return $datas;
    }
}
