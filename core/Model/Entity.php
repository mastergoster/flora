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
}
