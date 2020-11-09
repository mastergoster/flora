<?php

namespace Core\Controller;

use Core\Model\Entity;
use PHP_CodeSniffer\Generators\HTML;
use Cake\Database\ConstraintsInterface;

class FormController
{

    private $postDatas;

    private $fields = [];

    private $errors = [];

    private $datas = [];

    private $param = [];

    private $isverify = false;

    public function __construct()
    {
        if (count($_POST) > 0) {
            $this->postDatas = $_POST;
        } else {
            $this->newError("post", "no-data");
        }
    }

    /**
     * permet d'ajouter un champ et une contrainte
     *
     * @param string $field
     * @param array $constraints
     * @return self
     */
    public function field(string $field, array $constraints = [], array $params = []): self
    {
        //$field = 'mail'    $constraints =  ["require", "verify"]
        foreach ($constraints as $key => $value) {
            if (!is_string($key)) {
                unset($constraints[$key]);
                $constraints[$value] = true;
            }
        }
        $this->params[$field] =  $params;
        $this->fields[$field] =  $constraints;
        return $this;
    }

    public function hasErrors(): array
    {
        $this->verifyError();
        return $this->errors;
    }

    public function getDatas(): array
    {
        $this->verifyError();
        return $this->datas;
    }


    private function addToDatas(string $key, $data = null): void
    {
        if (is_null($data)) {
            if (\array_key_exists("safe", $this->params[$key])) {
                $data = $this->postDatas[$key];
            } else {
                $data = htmlspecialchars($this->postDatas[$key]);
            }
        }
        $this->datas[$key] = $data;
    }


    /**
     * @throws \Exception
     */
    private function verifyError(): void
    {
        if (!$this->isverify) {
            foreach ($this->fields as $field => $constraints) {
                //pas de contrainte defini
                if (count($constraints) <= 0) {
                    $this->addToDatas($field);
                }
                // boucle sur les contraintes pour appeler la methode de verif
                foreach ($constraints as $keyConstraint => $value) {
                    $constraint = "error" . ucfirst(strtolower($keyConstraint));
                    if (method_exists($this, $constraint)) {
                        $this->$constraint($field, $value);
                    } else {
                        throw new \Exception("La contrainte $keyConstraint n'existe pas", 1);
                    }
                }
            }
            $this->isverify = true;
        }
    }

    private function errorRequire(string $field, bool $value): void
    {
        $data = $this->postDatas[$field];

        if (isset($data) && (!empty($data) || $data === "0")) {
            $this->addToDatas($field);
        } else {
            $this->newError("$field", "le champ {$field} ne peut etre vide");
        }
    }

    private function errorVerify(string $field, bool $value): void
    {
        $fieldVerify = $field . "Verify";
        if (isset($this->postDatas[$fieldVerify]) &&
            $this->postDatas[$fieldVerify] == $this->postDatas[$field]
        ) {
            $this->addToDatas($field);
        } else {
            $this->newError("$field", "les champs {$field} doivent correspondre");
        }
    }

    private function errorLength(string $field, int $value): void
    {
        if (strlen($this->postDatas[$field]) >= $value) {
            $this->addToDatas($field);
        } else {
            $this->newError("$field", "le champ {$field} doit avoir au minimum {$value} caractères");
        }
    }
    private function errorExactLength(string $field, int $value): void
    {
        if (strlen($this->postDatas[$field]) == $value) {
            $this->addToDatas($field);
        } else {
            $this->newError("$field", "le champ {$field} doit avoir au minimum {$value} caractères");
        }
    }

    private function errorTel(string $field): void
    {

        $reg = "/^0[6-7]([0-9]{2}){4}$/";

        if (preg_match($reg, $this->postDatas[$field])) {
            $this->addToDatas($field);
        } else {
            $this->newError("$field", "le champ {$field} doit un numero de telephone valide");
        }
    }

    private function errorMail(string $field): void
    {

        if (filter_var($this->datas[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addToDatas($field);
        } else {
            $this->newError("$field", "le champ {$field} doit un email valide");
        }
    }
    private function errorBoolean(string $field): void
    {

        if ($this->postDatas[$field]) {
            $this->addToDatas($field, '1');
        } else {
            $this->addToDatas($field, '0');
        }
    }

    private function errorInt(string $field): void
    {
        $data = $this->datas[$field];
        $reg = "/^[0-9]*[\.\,]?[0-9]*$/";
        preg_match($reg, $data, $match);

        if ($match[0] !== null) {
            $this->addToDatas($field, str_replace(".", ",", $match[0]));
        } else {
            $this->newError("$field", "le champ {$field} doit un nombre");
        }
    }

    private function errorDate(string $field): void
    {
        $data = $this->datas[$field];
        $reg = "/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2}$/";
        preg_match($reg, $data, $match);

        if ($match[0] !== null) {
            $this->addToDatas($field, $match[0]);
        } else {
            $this->newError("$field", "le champ {$field} doit être une date au foramt 2020/11/20 12:30:00");
        }
    }

    private function newError(string $field, string $message): void
    {
        unset($this->datas[$field]);
        $this->errors["$field"][] = $message;
    }

    /**
     * Undocumented function
     *
     * @param boolean|Entity $entity
     * @return void
     */
    public function hydrate($entity = false)
    {
        if ($entity) {
            foreach ($this->fields as $field => $v) {
                $method = "get" . join(array_map(function ($cell) {
                    return ucfirst($cell);
                }, explode("_", $field)));
                $this->datas[$field] = $entity->$method();
            }
        }
        return $this;
    }

    public function html()
    {
        $html = "<form class=\"form-inline\" method=\"post\">";

        foreach ($this->fields as $field => $csonstraints) {
            $error = "";
            $errorDiv = "";
            $html .= "<div class=\"form-group mb-2\">";
            if (\key_exists($field, $this->errors) && !\key_exists('post', $this->errors)) {
                $error = " is-invalid";
            }
            if ($this->datas[$field]) {
                $value = " value=\"" . $this->datas[$field] . '"';
            } else {
                $value = "";
            }
            $html .= "<input type=\"text\" name=\"{$field}\" class=\"form-control{$error}\"" .
                " id=\"staticEmail2\" {$value} placeholder=\"{$field}\">";
            $html .= "</div>";
        }

        $html .= "<button type=\"submit\" class=\"btn btn-primary mb-2\">
            <span data-feather=\"check-square\"></span>
        </button>
    </form>";
        return $html;
    }
}
