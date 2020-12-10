<?php

namespace Tests\Core\Controller;

use App\Model\Entity\UsersEntity;
use Core\Controller\SecurityController;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\VarDumper\Cloner\Data;
use Tests\DatabaseTestCase;
use Tests\SessionTestCase;

class SecurityControllerTest extends TestCase
{

    public function testAccessRoleFalse()
    {
        $db = new DatabaseTestCase('test');
        $session = new SessionTestCase();

        $security = new SecurityController($db, $session);
        $this->assertEquals(false, $security->accessRole(20));
    }

    public function testAccessRoleTrueConnected()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(true, $security->accessRole(20));
    }
    public function testAccessRoleConnectedBadRole()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(2);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(false, $security->accessRole(30));
    }
    public function testAccessRoleConnectedGoodRoleNotActivate()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(3);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(false, $security->accessRole(30));
    }

    public function testAccessRoleNameTrueConnected()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(true, $security->accessRole('administrateur'));
    }

    public function testAccessRoleBadNameTrueConnectedAdministartor()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(true, $security->accessRole('administrator'));
    }
    public function testAccessRoleBadNameTrueConnectedLambda()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(2);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(false, $security->accessRole('administrator'));
    }

    public function testAccessRoleAdminByNoAdmin()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(2);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(false, $security->isAdmin());
    }
    public function testAccessRoleAdminByAdmin()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();

        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertEquals(true, $security->isAdmin());
    }

    public function testLogout()
    {
        $db = new DatabaseTestCase('test');
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $security->logout();
        $this->assertFalse($session->has("users"));
    }

    public function testIsconnectNoConnect()
    {
        $db = new DatabaseTestCase('test');
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->isConnect());
    }
    public function testIsconnectConnect()
    {
        $db = new DatabaseTestCase('test');
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertTrue($security->isConnect());
    }


    public function testLoginWithGoodUserAndPassword()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertTrue($security->login("test@test.fr", "password"));
        $this->assertTrue($session->has("users"));
    }

    public function testLoginWithGoodUserAndPin()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertTrue($security->login("test@test.fr", "1234", true));
        $this->assertFalse($session->has("users"));
    }

    public function testLoginWithGoodUserAndBadPassword()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->login("test@test.fr", "pascool"));
        $this->assertFalse($session->has("users"));
    }

    public function testLoginWithBadUserAndPassword()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->login("test22@test.fr", "pascool"));
        $this->assertFalse($session->has("users"));
    }

    public function testUpdatepasswordPasswordNoUser()
    {
        $db = new DatabaseTestCase('test');
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->updatePassword("test"));
    }

    public function testUpdatepasswordPassword()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertTrue($security->updatePassword("1234567"));
        $this->assertTrue($security->login("test@test.fr", "1234567"));
    }

    public function testHydrateSessionOneStatuts()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $security->userHydrateSession();
        $this->assertTrue($session->has('users'));
        $this->assertEquals(60, $session->get('users')->level);
    }
    public function testHydrateSessionTwoStatuts()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(4);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $security->userHydrateSession();
        $this->assertTrue($session->has('users'));
        $this->assertEquals('20', $session->get('users')->level);
    }
    public function testHydrateSessionTwoStatutsFalse()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(4);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $security->userHydrateSession();
        $this->assertTrue($session->has('users'));
        $this->assertNotEquals('40', $session->get('users')->level);
    }

    public function testIsNotActivate()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(3);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->isActivate());
    }

    public function testIsNotActivateWithGoodSessionToken()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(3);
        $session->set('users', $users);
        $session->set('validate', '987654321');
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->isActivate());
        $this->assertTrue($session->get('users')->getActivate());
    }
    public function testIsNotActivateWithBadSessionToken()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(3);
        $session->set('validate', '984321');
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->isActivate());
    }
    public function testIsActivate()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $users = new UsersEntity();
        $users->setId(1);
        $session->set('users', $users);
        $security = new SecurityController($db, $session);
        $this->assertTrue($security->isActivate());
    }
    public function testIsActivateNoUser()
    {
        $db = new DatabaseTestCase('test');
        $db->seedDatabase();
        $session = new SessionTestCase();
        $security = new SecurityController($db, $session);
        $this->assertFalse($security->isActivate());
    }
}
