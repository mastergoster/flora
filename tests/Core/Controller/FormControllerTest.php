<?php

namespace Tests\Core\Controller;

use \PHPUnit\Framework\TestCase;
use \Core\Controller\FormController;

class FormControllerTest extends TestCase
{

    public function testNewWithPost(): void
    {
        $_POST = [
            "mail" => "master@mater.fr",
            "mailVerify" => "master@mater.fr",
            "password" => "azerty",
            "passwordVerify" => "azerty"
        ];
        $form = new FormController();
        $this->assertEquals([], $form->hasErrors());
    }

    public function testNewWithOutPost(): void
    {
        $_POST = [];
        $form = new FormController();
        $errors = $form->hasErrors();
        $this->assertArrayHasKey("post", $errors);
        $this->assertEquals(['no-data'], $errors['post']);
    }

    public function testGetDatasWithoutFields(): void
    {
        $_POST = [
            "mail" => "master@mater.fr",
            "mailVerify" => "master@mater.fr",
            "password" => "azerty",
            "passwordVerify" => "azerty"
        ];
        $form = new FormController();
        $this->assertEquals([], $form->getDatas());
    }

    public function testFields(): void
    {
        $_POST = [
            "mail" => "master@mater.fr",
            "mailVerify" => "master@mater.fr",
            "password" => "azerty",
            "name" => "moimoi",

        ];
        $form = new FormController();
        $form->field('mail')
            ->field('password')
            ->field('name');

        $datas = $form->getDatas();

        $this->assertEquals(
            [
                "mail" =>  "master@mater.fr",
                "password" => "azerty",
                "name" => "moimoi"
            ],
            $datas
        );
    }

    public function testFieldsDatasWithErros(): void
    {
        $_POST = [
            "mail" => "master@mater.fr",
            "mailVerify" => "master@mater.r",
            "password" => "azd",
            "passwordVerify" => "azerty",
            "name" => ""
        ];
        $form = new FormController();
        $form->field('mail', ["verify"])
            ->field('password', ["length" => 4])
            ->field('name', ["require"]);
        $datas = $form->getDatas();

        $this->assertEquals(
            [],
            $datas
        );
    }

    public function testErrorsWithoutErrors(): void
    {
        $_POST = [
            "mail" => "master@mater.fr",
            "mailVerify" => "master@mater.fr",
            "password" => "azerty",
            "name" => "coucou"
        ];
        $form = new FormController();
        $form->field('name', ["require"]);
        $form->field('mail', ["verify"]);
        $form->field('password', ["length" => 4]);

        $errors = $form->HasErrors();

        $this->assertEquals(
            [],
            $errors
        );
    }

    public function testErrorsWithErrors(): void
    {
        $_POST = [
            "mail" => "master@mater.f",
            "mailVerify" => "master@mater.fr",
            "password" => "az",
            "name" => ""
        ];
        $form = new FormController();
        $form->field('mail', ["verify"]);
        $form->field('password', ["length" => 4]);
        $form->field('name', ["require"]);
        $errors = $form->HasErrors();

        $this->assertEquals(
            [
                'mail' => ['les champs mail doivent correspondre'],
                'password' => ['le champ password doit avoir au minimum 4 caractères'],
                'name' => ['le champ name ne peut etre vide']
            ],
            $errors
        );
    }

    public function testConstraintNotExist(): void
    {
        $_POST = [
            "mail" => "master@mater.f"
        ];
        $form = new FormController();
        $form->field('mail', ["notexiste"]);

        $this->expectExceptionMessage("La contrainte notexiste n'existe pas");

        $form->HasErrors();
    }
}
