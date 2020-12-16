<?php

namespace Core\Model;

use \Core\Controller\Database\DatabaseController;
use Core\Controller\Database\DatabaseMysqliteController;

abstract class Table
{
    /**
     * Undocumented variable
     *
     * @var DatabaseMysqliteController
     */
    protected $db;

    protected $table;

    public function __construct(DatabaseController $db)
    {
        $this->db = $db;

        if (is_null($this->table)) {
            $this->table = $this->extractTableName();
        }
    }

    public function extractTableName(): string
    {
        //App\Model\Table\MotMotMotusTable
        $parts = explode('\\', get_class($this));
        // [ "App", "Model", "Table", "MotMotMotusTable" ]
        $class_name = end($parts);
        //MotMotMotusTable
        $class_name = str_replace('Table', '', $class_name);
        //MotMotMotus
        $newTable = $class_name[0];
        // $newTable = M
        for ($i = 1; $i < strlen($class_name); $i++) {
            if (ctype_upper($class_name[$i])) {
                $newTable .= "_";
            }
            $newTable .= $class_name[$i];
        }
        // $newTable =  Mot_Mot_Motus

        $class_name = strtolower($newTable);
        // $newTable =  mot_mot_motus
        return $class_name;
    }

    public function count(): int
    {
        return count($this->query("SELECT id FROM {$this->table}", null, false, null));
    }

    public function last()
    {
        return $this->query("SELECT MAX(id) as lastId FROM {$this->table}", null, true)->lastId;
    }

    public function find($id, $column = 'id')
    {
        if (\is_array($id)) {
            foreach ($id as $k => $v) {
                $sql[] = "$k=:$k";
            }
            $sql = join(" AND ", $sql);
        } else {
            $sql = "$column=?";
            $id = [$id];
        }
        return $this->query("SELECT * FROM {$this->table} WHERE $sql", $id, true);
    }

    public function findAll($value, $column, $order = false, $colomunOrder = "id")
    {
        if ($order) {
            $order = " ORDER BY  UPPER(" . $colomunOrder . ") " . ($order === true ? "ASC" : "DESC");
        } else {
            $order = "";
        }
        return $this->query("SELECT * FROM {$this->table} WHERE $column=? {$order}", [$value]);
    }

    public function all($order = false, $colomun = "id")
    {
        if ($order) {
            $order = " ORDER BY  UPPER(" . $colomun . ") " . ($order === true ? "ASC" : "DESC");
        } else {
            $order = "";
        }
        return $this->query("SELECT * FROM $this->table" . $order);
    }

    public function create($fields, $class = false)
    {
        $sql_parts = [];
        $attributes = [];
        if ($class) {
            $fields = $this->objectToArray($fields);
        }
        foreach ($fields as $k => $v) {
            $sql_parts[] = $k;
            $sql_parts2[] = "?";
            $attributes[] = $v;
        }
        $sql_part = implode(', ', $sql_parts);
        $sql_part2 = implode(', ', $sql_parts2);
        return $this->query("INSERT INTO {$this->table} ({$sql_part}) VALUES ({$sql_part2})", $attributes, true);
    }
    public function updateByClass($obj, $columnDef = "id")
    {
        if (\is_object($obj)) {
            $fields = $this->objectToArray($obj);
        } else {
            throw new \Exception("ceci n'est pas un objet", 1);
        }
        return $this->update($fields[$columnDef], $columnDef, $fields);
    }

    public function update($id, $column, $fields)
    {
        $sql_parts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);
        return $this->query("UPDATE {$this->table} SET $sql_part WHERE $column = ?", $attributes, true);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id], true);
    }

    public function query(string $statement, ?array $attributes = null, bool $one = false, ?string $class_name = null)
    {
        if (is_null($class_name)) {
            $class_name = str_replace('Table', 'Entity', get_class($this));
        }

        if ($attributes) {
            return $this->db->prepare(
                $statement,
                $attributes,
                $class_name,
                $one
            );
        } else {
            return $this->db->query(
                $statement,
                $class_name,
                $one
            );
        }
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    protected function objectToArray($object)
    {
        $methods = get_class_methods($object);
        $array = [];
        foreach ($methods as $value) {
            if (strrpos($value, 'get') === 0) {
                if ($object->$value() !== null) {
                    $column = str_replace('get', '', $value);
                    $columname = $column[0];
                    for ($i = 1; $i < strlen($column); $i++) {
                        if (ctype_upper($column[$i])) {
                            $columname .= "_";
                        }
                        $columname .= $column[$i];
                    }
                    $array[strtolower($columname)] = $object->$value();
                }
            }
        }
        return $array;
    }
}
