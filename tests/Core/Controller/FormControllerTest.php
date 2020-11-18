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

    public function testHtmlOneField(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"text\" name=\"text\" class=\"form-control\" id=\"text\" placeholder=\"text\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";

        $form = new FormController();
        $form->field('text');
        $actual = $form->html();

        $this->assertEquals($expected, $actual);
    }

    public function testHtmlPasswordOneField(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"password\" name=\"password\" class=\"form-control\" id=\"password\"" .
            " placeholder=\"password\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";

        $form = new FormController();
        $form->field('password');
        $actual = $form->html();

        $this->assertEquals($expected, $actual);
    }
    public function testHtmlMessageOneField(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"text\" name=\"message\" class=\"form-control\" id=\"message\" placeholder=\"message\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";

        $form = new FormController();
        $form->field('message');
        $actual = $form->html();

        $this->assertEquals($expected, $actual);
    }
    public function testHtmlMailOneField(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"mail\" name=\"mail\" class=\"form-control\" id=\"mail\" placeholder=\"mail\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";

        $form = new FormController();
        $form->field('mail');
        $actual = $form->html();

        $this->assertEquals($expected, $actual);
    }
    public function testHtmlMailAndPassword(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"mail\" name=\"mail\" class=\"form-control\" id=\"mail\" placeholder=\"mail\">" .
            "</div>" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"password\" name=\"password\" class=\"form-control\" id=\"password\"" .
            " placeholder=\"password\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";

        $form = new FormController();
        $form->field('mail');
        $form->field('password');
        $actual = $form->html();

        $this->assertEquals($expected, $actual);
    }

    public function testHtmlMailAndPasswordVerifyOrder(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"password\" name=\"password\" class=\"form-control\" id=\"password\"" .
            " placeholder=\"password\">" .
            "</div>" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"mail\" name=\"mail\" class=\"form-control\" id=\"mail\" placeholder=\"mail\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";
        $form = new FormController();
        $form->field('password');
        $form->field('mail');
        $actual = $form->html();

        $this->assertEquals($expected, $actual);
    }

    public function testHtmlMailErrorNoPost(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"mail\" name=\"mail\" class=\"form-control\" id=\"mail\" placeholder=\"mail\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";
        $_POST = [];
        $form = new FormController();
        $form->field('mail', ['require']);
        $error = $form->hasErrors();
        $this->assertArrayHasKey("post", $error);
        $this->assertArrayHasKey("mail", $error);
        $actual = $form->html();
        $this->assertEquals($expected, $actual);
    }

    public function testHtmlMailError(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"mail\" name=\"mail\" class=\"form-control is-invalid\" id=\"mail\"" .
            " placeholder=\"mail\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";
        $_POST = ["machin" => "test"];
        $form = new FormController();
        $form->field('mail', ['require']);
        $error = $form->hasErrors();
        $actual = $form->html();
        $this->assertEquals($expected, $actual);
    }

    public function testHtmlMailPost(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"mail\" name=\"mail\" class=\"form-control\" id=\"mail\"" .
            " value=\"test@test.fr\" placeholder=\"mail\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";
        $_POST = ["mail" => "test@test.fr"];
        $form = new FormController();
        $form->field('mail');
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $actual = $form->html();
        $this->assertEquals($expected, $actual);
    }

    public function testHtmlPasswordPostNoDisplayData(): void
    {
        $expected = "<form class=\"form-inline\" method=\"post\">" .
            "<div class=\"form-group mb-2\">" .
            "<input type=\"password\" name=\"password\" class=\"form-control\"" .
            " id=\"password\" placeholder=\"password\">" .
            "</div>" .
            "<button type=\"submit\" class=\"btn btn-primary mb-2\">" .
            "<span data-feather=\"check-square\">" .
            "</span>" .
            "</button>" .
            "</form>";
        $_POST = ["password" => "monmotdepasse"];
        $form = new FormController();
        $form->field('password');
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $actual = $form->html();
        $this->assertEquals($expected, $actual);
    }
}
