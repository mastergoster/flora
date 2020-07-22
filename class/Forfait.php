<?php
class Forfait
{
    private $pdo;

    public function __construct(string $db = "database-forfait.sqlite")
    {
        try {
            $pdo = new PDO('sqlite:' . dirname(dirname(__FILE__)) . "/db/" . $db);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
        } catch (Exception $e) {
            echo "Impossible d'accéder à la base de données SQLite : " . $e->getMessage();
            die();
        }
        $pdo->query("CREATE TABLE IF NOT EXISTS forfait_log ( 
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            id_user VARCHAR( 250 ),
            id_forfait VARCHAR( 250 ),
            cearted_at VARCHAR( 250 )
        );");
        $pdo->query("CREATE TABLE IF NOT EXISTS forfait ( 
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom VARCHAR( 250 ),
            prix VARCHAR( 250 ),
            duree VARCHAR( 250 )
        );");
        $this->pdo = $pdo;
    }

    public function create($nom, $prix, $duree): array
    {
        $stmt = $this->pdo->prepare("INSERT INTO forfait (nom, prix, duree) VALUES (:nom, :prix, :duree)");
        $stmt->execute(array(
            'nom' => $nom,
            'prix' => $prix,
            'duree' => $duree
        ));
        return [
            'id' => $this->pdo->lastInsertId(),
            'nom' => $nom,
            'prix' => $prix,
            'duree' => $duree
        ];
    }
    public function ajout($id_user, $id_forfait, $cearted_at)
    {
        $stmt = $this->pdo->prepare("INSERT INTO forfait_log (id_user, id_forfait, cearted_at) VALUES (:id_user, :id_forfait, :cearted_at)");
        $stmt->execute(array(
            'id_user' => $id_user,
            'id_forfait' => $id_forfait,
            'cearted_at' => $cearted_at
        ));
        return [
            'id' => $this->pdo->lastInsertId(),
            'id_user' => $id_user,
            'prix' => $id_forfait,
            'cearted_at' => $cearted_at
        ];
    }

    public function list()
    {
        $stmt_forfait = $this->pdo->prepare("SELECT * FROM forfait");
        $stmt_forfait->execute();
        return $stmt_forfait->fetchAll();
    }

    public function listByUser(int $id)
    {
        $stmt_forfait = $this->pdo->prepare("   SELECT forfait_log.*, forfait.nom, forfait.duree
                                                FROM forfait_log 
                                                JOIN forfait ON forfait_log.id_forfait = forfait.id  
                                                WHERE id_user = ?
                                                ");
        $stmt_forfait->execute([$id]);
        $result =  $stmt_forfait->fetchAll();

        return $result;
    }

    public function resteByUser($id)
    {
        $stmt_forfait = $this->pdo->prepare("   SELECT forfait_log.*, forfait.nom, forfait.duree
        FROM forfait_log 
        JOIN forfait ON forfait_log.id_forfait = forfait.id  
        WHERE id_user = ? order by forfait_log.cearted_at DESC
        ");
        $stmt_forfait->execute([$id]);
        $results =  $stmt_forfait->fetchAll();
        if ($results) {
            $date1 = DateTime::createFromFormat('d-m-Y', $results[0]["cearted_at"]);
            $date1->modify('+3 month');
            $result = $results[0];
            $result["fin"] = $date1->format('d/m/Y');
            $duree = 0;
            foreach ($results as $ligne) {
                $duree += $ligne["duree"];
            }
            $result["h"] = $duree;
            $result["min"] = 0;
        } else {
            $result = [];
        }
        return $result;
    }

    public function del($id)
    {
        $stmt_forfait = $this->pdo->prepare("DELETE FROM forfait_log WHERE id = ?");
        return $stmt_forfait->execute([$id]);
    }
}
