<?php

namespace Tests\Core\Model;

use Exception;
use Tests\UserTestEntity;
use Tests\DatabaseTestCase;
use \PHPUnit\Framework\TestCase;
use Tests\Core\Model\ClassTest\UserTestTable;

class TableTest extends TestCase
{
    public function testExtractTableName()
    {
        $table = new ClassTest\MotMotMotusTable(new \Core\Controller\Database\DatabaseController());

        $this->assertEquals(
            "mot_mot_motus",
            $table->extractTableName()
        );
    }

    public function testExtractTableName2()
    {
        $table = new ClassTest\ClassNameTable(new \Core\Controller\Database\DatabaseController());
        $this->assertEquals(
            "class_name",
            $table->extractTableName()
        );
    }

    public function testCount()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->count();
        $this->assertEquals(4, $actual);
    }

    public function testLastId()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->last();
        $this->assertEquals(4, $actual);
    }

    public function testFindId()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->find(1);
        $this->assertEquals("test@test.fr", $actual->email);
        $this->assertEquals("1", $actual->id);
    }

    public function testFindEmail()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->find("test2@test.fr", 'email');
        $this->assertEquals("test2@test.fr", $actual->email);
        $this->assertEquals("2", $actual->id);
    }

    public function testFindMultipl()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->find([
            'email' => "test3@test.fr",
            'phone_number' => "0606060608"
        ]);
        $this->assertEquals("test3@test.fr", $actual->email);
        $this->assertEquals("3", $actual->id);
    }

    public function testFindAllToken()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->findAll("987654321", 'token');
        $this->assertIsArray($actual);
        $this->assertEquals(4, count($actual));
    }

    public function testAll()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->all();
        $this->assertIsArray($actual);
        $this->assertEquals(4, count($actual));
    }
    public function testAllRevese()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $actual = $users->all("DESC");

        $this->assertIsArray($actual);
        $this->assertEquals(4, count($actual));
        $this->assertEquals(4, $actual[0]->id);
    }

    public function testDelete()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $users = new UserTestTable($db);
        $users->delete(1);
        $result = $db->query("SELECT * from users");
        $this->assertEquals(3, count($result));
        $this->assertEquals(2, $result[0]->id);
    }

    public function testCreateWithArray()
    {
        $db = new DatabaseTestCase('test');
        $db->migrateDatabase();
        $users = new UserTestTable($db);
        $users->create([
            'password' => 'password',
            'email'    => "test@test.fr",
            'first_name'    => 'nameTest',
            'last_name'    => 'lastNameTest',
            'pin'    => '1234',
            'token'    => '987654321',
            'phone_number'    => '0606060606',
            'activate'    => '1',
            'verify'    => '1',
            "created_at" => "2020-12-14 10:37:56"
        ]);
        $user = new ClassTest\UserTestEntity();
        $user->id = 1;
        $user->password = 'password';
        $user->email = 'test@test.fr';
        $user->first_name = 'nameTest';
        $user->last_name = 'lastNameTest';
        $user->pin = '1234';
        $user->token = '987654321';
        $user->phone_number = '0606060606';
        $user->activate = '1';
        $user->verify = '1';
        $user->created_at = '2020-12-14 10:37:56';
        $user->street = null;
        $user->city = null;
        $user->desc = null;
        $user->id_images = null;
        $user->display = '0000';
        $result = $users->query("SELECT * FROM users");
        $this->assertEquals([$user], $result);
    }

    public function testCreateWithClass()
    {
        $db = new DatabaseTestCase('test');
        $db->migrateDatabase();
        $user = new ClassTest\UserTestEntity();
        $user->password = 'password';
        $user->email = 'test@test.fr';
        $user->first_name = 'nameTest';
        $user->last_name = 'lastNameTest';
        $user->pin = '1234';
        $user->token = '987654321';
        $user->phone_number = '0606060606';
        $user->activate = '1';
        $user->verify = '1';
        $user->created_at = '2020-12-14 10:37:56';
        $user->street = null;
        $user->city = null;
        $user->desc = null;
        $user->id_images = null;
        $user->display = '0000';


        $users = new UserTestTable($db);
        $users->create($user, true);

        $user->id = 1;
        $result = $users->query("SELECT * FROM users");
        $this->assertEquals([$user], $result);
    }

    public function testUpdateByClassException()
    {
        $db = new DatabaseTestCase('test');
        $users = new UserTestTable($db);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("ceci n'est pas un objet");
        $this->expectExceptionCode(1);
        $users->updateByClass('not a object');
    }

    public function testUpdateByClassWithClass()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $user = new ClassTest\UserTestEntity();
        $user->id = '1';
        $user->password = 'password';
        $user->email = 'michel@test.fr';
        $user->first_name = 'moi';
        $user->last_name = 'toi';
        $user->pin = '2344';
        $user->token = '987654321';
        $user->phone_number = '0606060699';
        $user->activate = '1';
        $user->verify = '1';
        $user->created_at = '2020-12-14 10:37:56';
        $user->street = null;
        $user->city = null;
        $user->desc = null;
        $user->id_images = null;
        $user->display = '0000';
        $users = new UserTestTable($db);
        $users->updateByClass($user);

        $result = $users->find(1);
        $this->assertEquals($user, $result);
    }

    public function testUpdateByClassWithClassByToken()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $user = new ClassTest\UserTestEntity();
        $user->id = '1';
        $user->password = 'password';
        $user->email = 'test@test.fr';
        $user->first_name = 'moi';
        $user->last_name = 'toi';
        $user->pin = '2344';
        $user->token = '987654321';
        $user->phone_number = '0606060699';
        $user->activate = '1';
        $user->verify = '1';
        $user->created_at = '2020-12-14 10:37:56';
        $user->street = null;
        $user->city = null;
        $user->desc = null;
        $user->id_images = null;
        $user->display = '0000';
        $users = new UserTestTable($db);
        $users->updateByClass($user, "email");

        $result = $users->find(1);
        $this->assertEquals($user, $result);
    }

    public function testLastInsertId()
    {
        $db = new DatabaseTestCase('test');
        $db->migrateDatabase();
        $user = new ClassTest\UserTestEntity();
        $user->password = 'password';
        $user->email = 'test@test.fr';
        $user->first_name = 'nameTest';
        $user->last_name = 'lastNameTest';
        $user->pin = '1234';
        $user->token = '987654321';
        $user->phone_number = '0606060606';
        $user->activate = '1';
        $user->verify = '1';
        $user->created_at = '2020-12-14 10:37:56';

        $users = new UserTestTable($db);
        $users->create($user, true);
        $this->assertEquals(1, $users->lastInsertId());
    }
}
