<?php

namespace Tests\Core\Controller\Helpers;

use Tests\UserTestEntity;
use \PHPUnit\Framework\TestCase;
use Core\Controller\Helpers\TableauController;

class TableauControllerTest extends TestCase
{

    public function testassocId()
    {
        $user1 = new UserTestEntity;
        $user1->setId(1);
        $user2 = new UserTestEntity;
        $user2->setId(2);
        $user3 = new UserTestEntity;
        $user3->setId(3);

        $actual = [$user1, $user2, $user3];
        $expected = [1 => $user1, 2 => $user2, 3 => $user3];
        $this->assertEquals($expected, TableauController::assocId($actual));
    }

    public function testassocName()
    {
        $user1 = new UserTestEntity;
        $user1->setId(1);
        $user1->setName("julien");
        $user2 = new UserTestEntity;
        $user2->setId(2);
        $user2->setName("pierre");
        $user3 = new UserTestEntity;
        $user3->setId(3);
        $user3->setName("henry");


        $actual = [$user1, $user2, $user3];
        $expected = ["julien" => $user1, "pierre" => $user2, "henry" => $user3];
        $this->assertEquals($expected, TableauController::assocId($actual, 'name'));
    }
}
