<?php
class Heures
{
    private $pdo;

    public function __construct(string $db = "database-heures.sqlite")
    {
        try {
            $pdo = new PDO('sqlite:' . dirname(dirname(__FILE__)) . "/db/" . $db);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
        } catch (Exception $e) {
            echo "Impossible d'accéder à la base de données SQLite : " . $e->getMessage();
            die();
        }
        $pdo->query("CREATE TABLE IF NOT EXISTS heures ( 
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            id_user VARCHAR( 250 ),
            cearted_at VARCHAR( 250 )
        );");
        $this->pdo = $pdo;
    }


    public function ajout($id_user)
    {
        $heure = date('d-m-Y H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO heures (id_user, cearted_at) VALUES (:id_user, :cearted_at)");
        $stmt->execute(array(
            'id_user' => $id_user,
            'cearted_at' => $heure
        ));
        return [
            'id' => $this->pdo->lastInsertId(),
            'id_user' => $id_user,
            'cearted_at' => $heure
        ];
    }

    public function list()
    {
        $stmt_forfait = $this->pdo->prepare("SELECT * FROM heures");
        $stmt_forfait->execute();
        return $stmt_forfait->fetchAll();
    }

    public function listByUser(int $id)
    {
        $stmt_forfait = $this->pdo->prepare("SELECT * FROM heures WHERE id_user = ? order by cearted_at DESC");
        $stmt_forfait->execute([$id]);
        $result =  $stmt_forfait->fetchAll();
        if (!$result) {
            $result = [];
        }
        return $result;
    }

    public function del($id)
    {
        $stmt_forfait = $this->pdo->prepare("DELETE FROM heures WHERE id = ?");
        return $stmt_forfait->execute([$id]);
    }

    public function presence($id)
    {
        $now = date('d-m-Y');
        $list = $this->listByUser($id);
        $present = [];
        foreach ($list as $ligne) {
            if (substr($ligne["cearted_at"], 0, 10) == $now) {
                $present[] = $ligne;
            }
        }
        if (count($present) < 1) {
            return false;
        } elseif (count($present) % 2) {
            return true;
        } else {
            return false;
        }
    }
}
