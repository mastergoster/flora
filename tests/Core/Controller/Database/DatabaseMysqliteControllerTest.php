<?php

namespace Tests\Core\Controller\Database;

use PDO;
use App\App;
use stdClass;
use PDOStatement;
use Tests\UserTestEntity;
use Tests\DatabaseTestCase;
use \PHPUnit\Framework\TestCase;
use Core\Controller\Database\DatabaseMysqliteController;

class DatabaseMysqliteControllerTest extends TestCase
{
    private $pdo;

    private $nameDatabase;

    private $fileName;

    public function __construct()
    {
        parent::__construct();
        $number = "";
        while (file_exists(\App\App::rootfolder()
            . DIRECTORY_SEPARATOR
            . "db"
            . DIRECTORY_SEPARATOR . "test{$number}.sqlite")) {
            $number++;
        }
        $this->fileName = "test{$number}";
        $this->nameDatabase = \App\App::rootfolder()
            . DIRECTORY_SEPARATOR
            . "db"
            . DIRECTORY_SEPARATOR . $this->fileName . ".sqlite";
    }

    protected function tearDown(): void
    {
        $this->getPdo()->query('DROP TABLE user');
        if (DIRECTORY_SEPARATOR == "/") {
            unlink($this->nameDatabase);
        }
    }

    public function testGetPdo()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);
        $bdd->getPDO();
        $this->assertFileExists($this->nameDatabase);
    }

    public function testQueryInsert()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();

        $result = $pdo->query('SELECT * FROM user')->fetchAll();
        $this->assertEquals([], $result);

        $actual = $bdd->query("INSERT INTO user (nom) VALUES ('julien')");
        $this->assertInstanceOf(PDOStatement::class, $actual);

        $user = new stdClass;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $pdo->query('SELECT * FROM user')->fetchAll();
        $this->assertEquals([$user], $result);
    }

    public function testPrepareInsert()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();

        $result = $pdo->query('SELECT * FROM user')->fetchAll();
        $this->assertEquals([], $result);

        $actual = $bdd->prepare("INSERT INTO user (nom) VALUES (?)", ["julien"]);
        $this->assertTrue($actual);

        $user = new stdClass;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $pdo->query('SELECT * FROM user')->fetchAll();
        $this->assertEquals([$user], $result);
    }

    public function testPrepareObj()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();
        $pdo->query("INSERT INTO user (nom) VALUES ('julien')");


        $user = new stdClass;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $bdd->prepare('SELECT * FROM user WHERE nom=?', ["julien"]);
        $this->assertEquals([$user], $result);
    }

    public function testPrepareUserTestEntityOne()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();
        $pdo->query("INSERT INTO user (nom) VALUES ('julien')");


        $user = new UserTestEntity;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $bdd->prepare('SELECT * FROM user WHERE nom=?', ['julien'], UserTestEntity::class, true);
        $this->assertInstanceOf(UserTestEntity::class, $result);
        $this->assertEquals($user, $result);
    }

    public function testPrepareUserTestEntityTwo()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();
        $pdo->query("INSERT INTO user (nom) VALUES ('julien')");
        $pdo->query("INSERT INTO user (nom) VALUES ('pierre')");


        $user = new UserTestEntity;
        $user->nom = 'julien';
        $user->id = '1';
        $user2 = new UserTestEntity;
        $user2->nom = 'pierre';
        $user2->id = '2';
        $result = $bdd->prepare('SELECT * FROM user', [], UserTestEntity::class);
        $this->assertIsArray($result);
        $this->assertEquals([$user, $user2], $result);
    }

    public function testQueryObj()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();
        $pdo->query("INSERT INTO user (nom) VALUES ('julien')");


        $user = new stdClass;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $bdd->query('SELECT * FROM user');
        $this->assertEquals([$user], $result);
    }

    public function testQueryUserTestEntityOne()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();
        $pdo->query("INSERT INTO user (nom) VALUES ('julien')");


        $user = new UserTestEntity;
        $user->nom = 'julien';
        $user->id = '1';
        $result = $bdd->query('SELECT * FROM user', UserTestEntity::class, true);
        $this->assertInstanceOf(UserTestEntity::class, $result);
        $this->assertEquals($user, $result);
    }

    public function testQueryUserTestEntityTwo()
    {
        $bdd = new DatabaseMysqliteController($this->fileName);

        $pdo = $this->getPdo();
        $pdo->query("INSERT INTO user (nom) VALUES ('julien')");
        $pdo->query("INSERT INTO user (nom) VALUES ('pierre')");


        $user = new UserTestEntity;
        $user->nom = 'julien';
        $user->id = '1';
        $user2 = new UserTestEntity;
        $user2->nom = 'pierre';
        $user2->id = '2';
        $result = $bdd->query('SELECT * FROM user', UserTestEntity::class);
        $this->assertIsArray($result);
        $this->assertEquals([$user, $user2], $result);
    }

    public function testLastInsertId()
    {
        $this->getPdo();
        $bdd = new DatabaseMysqliteController($this->fileName);
        $bdd->query("INSERT INTO user (id, nom) VALUES ('2', 'julien')");
        $result = $bdd->lastInsertId();
        $this->assertEquals('2', $result);
    }









    private function getPdo()
    {
        if (is_null($this->pdo)) {
            $pdo = new PDO(
                'sqlite:' . $this->nameDatabase
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
