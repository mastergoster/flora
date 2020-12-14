<?php

namespace Tests;

class UserTestEntity
{
    public $id;

    public $name;

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
}
