<?php
class Adherant
{
    private $pdo;

    public function __construct(string $db = "database-user.sqlite")
    {
        try {
            $pdo = new PDO('sqlite:' . dirname(dirname(__FILE__)) . "/db/" . $db);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
        } catch (Exception $e) {
            echo "Impossible d'accéder à la base de données SQLite : " . $e->getMessage();
            die();
        }
        $pdo->query("CREATE TABLE IF NOT EXISTS adherant ( 
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom VARCHAR( 250 ),
            prenom VARCHAR( 250 ),
            mail VARCHAR( 250 ),
            mdp VARCHAR( 250 ),
            pin VARCHAR( 255 ),
            tel VARCHAR( 255 ),
            activate  VARCHAR( 250 )
        );");
        $this->pdo = $pdo;
    }

    public function create($lastName, $firstName, $email, $mdp, $pin, $tel)
    {
        $stmt = $this->pdo->prepare("INSERT INTO adherant (nom, prenom, mail, mdp, pin, tel, activate) VALUES (:lastName, :firstName, :email, :mdp, :pin, :tel, :activate)");
        $result = $stmt->execute(array(
            'firstName' => $lastName,
            'lastName' => $firstName,
            'email' => $email,
            'tel' => $tel,
            'mdp' => password_hash($mdp, PASSWORD_BCRYPT),
            'pin' => $pin,
            'activate' => '1'
        ));
        return $this->pdo->lastInsertId();;
    }


    public function list()
    {
        $stmt_forfait = $this->pdo->prepare("SELECT * FROM adherant");
        $stmt_forfait->execute();
        return $stmt_forfait->fetchAll();
    }

    public function listByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM adherant WHERE mail=?");
        $stmt->execute([$email]);
        $ad = $stmt->fetch();
        return $ad;
    }

    public function listById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM adherant WHERE id=?");
        $stmt->execute([$id]);
        $ad = $stmt->fetch();
        return $ad;
    }

    public function del($id)
    {
    }
}
