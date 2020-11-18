<?php

namespace Tests\Core\Model;

use \PHPUnit\Framework\TestCase;
use Tests\Core\Model\ClassTest\EntityTestEntity;

class EntityTest extends TestCase
{
    public function testHydrate()
    {
        $entity = new EntityTestEntity();
        $datas = ["nom" => "Pierre", "prenom" => "jen-pierre", "createdAt" => "2020-12-20 09:00:01"];
        $entity2 = new EntityTestEntity();
        $entity2->setNom("Pierre");
        $entity2->setPrenom("jen-pierre");
        $entity2->setCreatedAt("2020-12-20 09:00:01");

        $entity->hydrate($datas);
        $this->assertEquals($entity2, $entity);
    }
}
