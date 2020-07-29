<?php

namespace Core\Controller;

use \Core\Model\Table;

class PaginatedQueryController
{
    private $classTable;

    private $url;

    private $perPage;

    private $items;

    private $count;

    public function __construct(
        Table $classTable,
        string $url = null,
        int    $perPage = 12
    ) {
        $this->classTable   = $classTable;
        $this->url          = $url;
        $this->perPage      = $perPage;
    }

    public function getItems(): array
    {
        $nbPage = $this->getNbPages();
        $currentPage = $this->getCurrentPage();
        if ($currentPage > $nbPage) {
            throw new \Exception('pas de pages');
        }
        if ($this->items === null) {
            $offset = ($currentPage - 1) * $this->perPage;
            $this->items = $this->classTable->allByLimit($this->perPage, $offset);
        }
        return $this->items;
    }

    public function getItemsInId(int $id): array
    {
        $nbPage = $this->getNbPages($id);
        $currentPage = $this->getCurrentPage();
        if ($currentPage > $nbPage) {
            throw new \Exception('pas de pages');
        }
        if ($this->items === null) {
            $offset = ($currentPage - 1) * $this->perPage;
            $this->items = $this->classTable->allInIdByLimit($this->perPage, $offset, $id);
        }
        return $this->items;
    }

    public function getNav(): array
    {
        $uri = $this->url;
        $nbPage = $this->getNbPages();
        $navArray = [];
        for ($i = 1; $i <= $nbPage; $i++) {
            // if($i == 1){
            //     $url = $uri;
            // }else{
            //     $url = $uri . "?page=" . $i;
            // }
            $url = $i == 1 ? $uri : $uri . "?page=" . $i;

            $navArray[$i] = $url;
        }
        return $navArray;
    }

    protected function getCurrentPage(): int
    {
        return URLController::getPositiveInt('page', 1);
    }


    private function getNbPages(?int $id = null): float
    {
        if ($this->count === null) {
            if ($id === null) {
                $this->count = $this->classTable->count()->nbrow;
            } else {
                $this->count = $this->classTable->countByid($id)->nbrow;
            }
        }
        return ceil($this->count / $this->perPage);
    }
}
