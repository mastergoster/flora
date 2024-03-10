<?php

namespace App\Model\Table;

use Core\Model\Table;

class BooksTable extends Table
{
    public function all($order = false, $colomun = "id")
    {
        if ($order) {
            $order = " ORDER BY  UPPER(" . $colomun . ") " . ($order === true ? "ASC" : "DESC");
        } else {
            $order = "";
        }
        return $this->query(
            "SELECT 
                $this->table.*,
                ressources.name AS ressource_name,
                ressources.slug AS ressource_slug,
                ressources.color AS ressource_color
            FROM $this->table
            LEFT JOIN ressources ON $this->table.id_ressource = ressources.id 
            $order"
        );
    }

    public function verify($salle, $start , $end, $idbdd = null)
    {
        
        return $this->query(
            "SELECT *
            FROM $this->table
            left join ressources on $this->table.id_ressource = ressources.id
            WHERE 
                $this->table.id != :idbdd
                AND ressources.slug = :salle
                AND (
                    (start_at BETWEEN :start AND :end)
                    OR (end_at BETWEEN :start AND :end)
                    OR (start_at < :start AND end_at > :end)
                )",
            ["idbdd"=>$idbdd, "salle" =>$salle, "start" => $start, "end" => $end]
        );

    }
}
