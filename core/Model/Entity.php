<?php

namespace Core\Model;

abstract class Entity
{
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $methode = 'set' . ucfirst($key);
            if (method_exists($this, $methode)) {
                $this->$methode(htmlspecialchars($value));
            }
        }
    }

    public function __toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            $methode = 'get' . ucfirst($key);
            if (method_exists($this, $methode)) {
                $array[$key] = $this->$methode();
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }
}
