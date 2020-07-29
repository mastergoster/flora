<?php

namespace Core\Controller;

class FormController
{

    private $postDatas;

    private $fields = [];

    private $errors = [];

    private $datas = [];

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
    public function field(string $field, array $constraints = []): self
    {
        //$field = 'mail'    $constraints =  ["require", "verify"]
        foreach ($constraints as $key => $value) {
            if (!is_string($key)) {
                unset($constraints[$key]);
                $constraints[$value] = true;
            }
        }
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
            $data = htmlspecialchars($this->postDatas[$key]);
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
        if (isset($data) && !empty($data)) {
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

    private function newError(string $field, string $message): void
    {
        unset($this->datas[$field]);
        $this->errors["$field"][] = $message;
    }
}
