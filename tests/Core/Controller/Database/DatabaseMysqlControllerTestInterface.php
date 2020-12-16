<?php

namespace Tests\Core\Controller\Database;

use \PDO;

use App\App;
use Core\Controller\Database\DatabaseMysqlController;

class DatabaseMysqlControllerTestInterface extends DatabaseMysqlController
{
    private $pdo;

    public function getPDO()
    {
        if (is_null($this->pdo)) {
            $pdo = new PDO(
                'sqlite:' . App::rootfolder() . "/db/" . $this->db_name . ".sqlite"
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo = $pdo;
        }

        return $this->pdo;
    }
}
