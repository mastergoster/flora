<?php

namespace Tests\Core\Controller;

use \PHPUnit\Framework\TestCase;
use \Core\Controller\FormController;
use Tests\UserTestEntity;

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

    public function testConstraintTel07(): void
    {
        $_POST = ["tel" => "0700000000"];
        $form = new FormController();
        $form->field('tel', ["tel"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(['tel' => "0700000000"], $datas);
    }
    public function testConstraintTel06(): void
    {
        $_POST = ["tel" => "0600000000"];
        $form = new FormController();
        $form->field('tel', ["tel"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(['tel' => "0600000000"], $datas);
    }

    public function testConstraintTelBadNumber(): void
    {
        $_POST = ["tel" => "0000000"];
        $form = new FormController();
        $form->field('tel', ["tel"]);
        $error = $form->hasErrors();
        $this->assertEquals(["tel" => ["le champ tel doit un numero de telephone valide"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testConstraintTelFixNumber01(): void
    {
        $_POST = ["tel" => "0100000000"];
        $form = new FormController();
        $form->field('tel', ["tel"]);
        $error = $form->hasErrors();
        $this->assertEquals(["tel" => ["le champ tel doit un numero de telephone valide"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }
    public function testConstraintTelFixNumber04(): void
    {
        $_POST = ["tel" => "0400000000"];
        $form = new FormController();
        $form->field('tel', ["tel"]);
        $error = $form->hasErrors();
        $this->assertEquals(["tel" => ["le champ tel doit un numero de telephone valide"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testConstraintExactLength9(): void
    {
        $_POST = ["tel" => "040000000"];
        $form = new FormController();
        $form->field('tel', ["ExactLength" => 10]);
        $error = $form->hasErrors();
        $this->assertEquals(["tel" => ["le champ tel doit avoir 10 caractères"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testConstraintExactLength11(): void
    {
        $_POST = ["tel" => "04000000000"];
        $form = new FormController();
        $form->field('tel', ["ExactLength" => 10]);
        $error = $form->hasErrors();
        $this->assertEquals(["tel" => ["le champ tel doit avoir 10 caractères"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testConstraintExactLength10(): void
    {
        $_POST = ["tel" => "0400000000"];
        $form = new FormController();
        $form->field('tel', ["ExactLength" => 10]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(['tel' => "0400000000"], $datas);
    }

    public function testConstraintBadMail(): void
    {
        $_POST = ["mail" => "master@bad"];
        $form = new FormController();
        $form->field('mail', ["mail"]);
        $error = $form->hasErrors();
        $this->assertEquals(["mail" => ["le champ mail doit un email valide"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }
    public function testConstraintGoodMail(): void
    {
        $_POST = ["email" => "good@mail.com"];
        $form = new FormController();
        $form->field("email", ["mail"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(["email" => "good@mail.com"], $datas);
    }

    public function testConstraintBoolean1(): void
    {
        $_POST = ["boolean" => "1"];
        $form = new FormController();
        $form->field("boolean", ["boolean"]);
        $errorb = $form->hasErrors();
        $this->assertEquals([], $errorb);
        $datas = $form->getDatas();
        $this->assertEquals(["boolean" => 1], $datas);
    }

    public function testConstraintBoolean0(): void
    {
        $_POST = ["boolean" => "0"];
        $form = new FormController();
        $form->field("boolean", ["boolean"]);
        $errorb = $form->hasErrors();
        $this->assertEquals([], $errorb);
        $datas = $form->getDatas();
        $this->assertEquals(["boolean" => 0], $datas);
    }

    public function testConstraintBooleanNo(): void
    {
        $_POST = ["name"];
        $form2 = new FormController();
        $form2->field("boolean", ["boolean"]);
        $errorb = $form2->hasErrors();
        $this->assertEquals([], $errorb);
        $datas = $form2->getDatas();
        $this->assertEquals(["boolean" => 0], $datas);
    }

    public function testConstraintNumberValid(): void
    {
        $_POST = ["chiffre" => "1200"];
        $form = new FormController();
        $form->field("chiffre", ["int"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(["chiffre" => "1200"], $datas);
    }

    public function testConstraintNumberValidPoint(): void
    {
        $_POST = ["chiffre" => "12.00"];
        $form = new FormController();
        $form->field("chiffre", ["int"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(["chiffre" => "12,00"], $datas);
    }

    public function testConstraintNumberValidVirgule(): void
    {
        $_POST = ["chiffre" => "12,00"];
        $form = new FormController();
        $form->field("chiffre", ["int"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(["chiffre" => "12,00"], $datas);
    }

    public function testConstraintNumberInValid(): void
    {
        $_POST = ["chiffre" => "julien"];
        $form = new FormController();
        $form->field("chiffre", ["int"]);
        $error = $form->hasErrors();
        $this->assertEquals(["chiffre" => ["le champ chiffre doit un nombre"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testConstraintDateInValid(): void
    {
        $_POST = ["date" => "julien"];
        $form = new FormController();
        $form->field("date", ["date"]);
        $error = $form->hasErrors();
        $this->assertEquals(["date" => ["le champ date doit être une date au foramt 2020-11-20 12:30:00"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testConstraintDateValid(): void
    {
        $_POST = ["date" => "2020-11-20 12:30:00"];
        $form = new FormController();
        $form->field("date", ["date"]);
        $error = $form->hasErrors();
        $this->assertEquals([], $error);
        $datas = $form->getDatas();
        $this->assertEquals(["date" => "2020-11-20 12:30:00"], $datas);
    }

    public function testDoubleConstraintValidAndInvalid(): void
    {
        $_POST = ["mail" => "badEmail.fr"];
        $form = new FormController();
        $form->field("mail", ["require", "mail"]);
        $error = $form->hasErrors();
        $this->assertEquals(["mail" => ["le champ mail doit un email valide"]], $error);
        $datas = $form->getDatas();
        $this->assertEquals([], $datas);
    }

    public function testHydrate(): void
    {
        $_POST = ["date" => ""];
        $form = new FormController();
        $form->field("id");
        $form->field("name");
        $user = new UserTestEntity;
        $user->setId(1);
        $user->setname("julien");
        $error = $form->hasErrors();
        $form->hydrate($user);
        $datas = $form->getDatas();
        $this->assertEquals(["id" => 1, "name" => "julien"], $datas);
    }

    public function testAdToDataSafe(): void
    {
        $_POST = ["message" => "<script>alert(\"coucou\")</script>"];
        $form = new FormController();
        $form->field('message', [], ["safe" => true]);
        $error = $form->hasErrors();
        $datas = $form->getDatas();
        $this->assertEquals(["message" => "<script>alert(\"coucou\")</script>"], $datas);
    }
    public function testAdToDataNoSafe(): void
    {
        $_POST = ["message" => "<script>alert(\"coucou\")</script>"];
        $form = new FormController();
        $form->field('message');
        $error = $form->hasErrors();
        $datas = $form->getDatas();
        $this->assertEquals(["message" => \htmlspecialchars($_POST["message"])], $datas);
    }
}
