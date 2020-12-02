<?php

namespace Tests\Core\Controller;

use PDO;
use App\App;
use Tests\DatabaseTestCase;
use \PHPUnit\Framework\TestCase;
use Core\Controller\Database\DatabaseMysqliteController;
use PDOStatement;
use stdClass;

class DatabaseMysqliteControllerTest extends TestCase
{
    private $pdo;

    public static function tearDownAfterClass(): void
    {
        $FileName = \App\App::rootfolder() . DIRECTORY_SEPARATOR . "db" . DIRECTORY_SEPARATOR . "test.sqlite";
        chmod($FileName, 0777);
        unlink($FileName);

        // if (!unlink(\App\App::rootfolder() . DIRECTORY_SEPARATOR . "db" . DIRECTORY_SEPARATOR . "/test.sqlite")) {
        //     dd("unlink False");
        // };
    }


    public function testGetPdo()
    {
        $bdd = new DatabaseMysqliteController('test');
        $bdd->getPDO();
        $this->assertFileExists(\App\App::rootfolder()
            . DIRECTORY_SEPARATOR
            . "db"
            . DIRECTORY_SEPARATOR . "test.sqlite");
    }

    public function testQueryInsert()
    {
        $bdd = new DatabaseMysqliteController('test');

        $pdo = $this->getPdo();

        $result = $pdo->query('SELECT * FROM user')->fetchAll();
        $this->assertEquals([], $result);

        $actual = $bdd->query("INSERT INTO user  (nom) VALUES ('julien')");
        $this->assertInstanceOf(PDOStatement::class, $actual);

        $user = new stdClass;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $pdo->query('SELECT * FROM user')->fetchAll();
        $this->assertEquals([$user], $result);
    }

    public function testLastInsertId()
    {
        $bdd = new DatabaseMysqliteController('test');
        $bdd->query("INSERT INTO user (id, nom) VALUES ('2', 'julien')");
        $result = $bdd->lastInsertId();
        $this->assertEquals('2', $result);
    }









    private function getPdo()
    {
        if (is_null($this->pdo)) {
            $pdo = new PDO(
                'sqlite:' . App::rootfolder() . "/db/test.sqlite"
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo = $pdo;
        }
        $this->pdo->query('CREATE TABLE IF NOT EXISTS `user` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `nom` varchar(100) NOT NULL)');
        return $this->pdo;
    }
}
