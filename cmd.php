#!/usr/bin/env php
<?php


declare(strict_types=1);
$config = require_once("config.php");
if (php_sapi_name() != "cli") {
  fwrite(
    STDERR,
    PHP_EOL .
      'fichier uniquement pour ligne de commande.' . PHP_EOL .
      'Plus d\'infos https://github.com/mastergoster.' . PHP_EOL . PHP_EOL
  );

  die(1);
}



$demo = false;

foreach ($argv as $value) {
  if ($value === "--demo") {
    $demo = true;
  }
  if ($value === "-h" || $value === "--help") {
    echo "\nPour inserer les données de demo utilisé le flag --demo\n\n";
    exit();
  }
}


require_once 'vendor/autoload.php';

$pdo = new PDO('sqlite:db/application.sqlite');

/**
 * Suppression de table
 */
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');


$pdo->exec('DROP TABLE user');
$pdo->exec('DROP TABLE forfait_log');
$pdo->exec('DROP TABLE forfait');
$pdo->exec('DROP TABLE heures');
$pdo->exec('DROP TABLE role_liaison');
$pdo->exec('DROP TABLE role');
$pdo->exec('DROP TABLE compta_ligne');
$pdo->exec('DROP TABLE compta_ndf');


$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

/**
 * creation tables
 * 
 */

echo "[";
$etape = $pdo->exec("CREATE TABLE IF NOT EXISTS user ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom VARCHAR( 250 ),
  prenom VARCHAR( 250 ),
  mail VARCHAR( 250 ),
  password VARCHAR( 250 ),
  pin VARCHAR( 255 ),
  token VARCHAR( 255 ),
  tel VARCHAR( 255 ),
  activate  VARCHAR( 250 ),
  verify VARCHAR( 255 )
);");
echo "||";
$etape = $pdo->exec("CREATE TABLE IF NOT EXISTS forfait_log ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  id_user VARCHAR( 250 ),
  id_forfait VARCHAR( 250 ),
  created_at VARCHAR( 250 )
);");
echo "||";
$pdo->exec("CREATE TABLE IF NOT EXISTS forfait ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom VARCHAR( 250 ),
  prix VARCHAR( 250 ),
  duree VARCHAR( 250 )
);");
echo "||";

$pdo->exec("CREATE TABLE IF NOT EXISTS heures ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  id_user VARCHAR( 250 ),
  created_at VARCHAR( 250 )
);");
echo "||";

$pdo->exec("CREATE TABLE IF NOT EXISTS role ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom VARCHAR( 250 ),
  activate VARCHAR( 250 )
);");

echo "||";

$pdo->exec("CREATE TABLE IF NOT EXISTS role_liaison ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  id_role VARCHAR( 250 ),
  id_user VARCHAR( 250 ),
  created_at VARCHAR( 250 )
);");

echo "||";

$pdo->exec("CREATE TABLE IF NOT EXISTS compta_ligne ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  `description` VARCHAR( 250 ),
  `debit` VARCHAR( 250 ),
  `credit` VARCHAR( 250 ),
  `date` VARCHAR( 250 ),
  created_at VARCHAR( 250 )
);");

echo "||";

$pdo->exec("CREATE TABLE IF NOT EXISTS compta_ndf ( 
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  `description` VARCHAR( 250 ),
  `id_user` VARCHAR( 250 ),
  `id_ligne` VARCHAR( 250 ),
  `paye_at` VARCHAR( 250 ),
  created_at VARCHAR( 250 )
);");

echo "||||||||||||]
";

/**
 * remplissage des tables
 */

echo "[||||";
$pdo->exec(
  "INSERT INTO role 
        (nom, activate)
        VALUES 
        ('aucun','1')"
);
echo "||||";
$pdo->exec(
  "INSERT INTO role 
        (nom, activate)
        VALUES 
        ('administrateur','1')"
);
echo "||||";
$pdo->exec(
  "INSERT INTO role 
        (nom, activate)
        VALUES 
        ('adherants','1')"
);
echo "||||";

$pdo->exec(
  "INSERT INTO role 
        (nom, activate)
        VALUES 
        ('membre actif','1')"
);
echo "||||";

$password = password_hash($config["passwordadmin"], PASSWORD_BCRYPT);

$pdo->exec(
  "INSERT INTO user 
        (nom, prenom, mail, password, pin, token, tel, activate, verify)
        VALUES 
        ('admin','admin','" . $config['usernameadmin'] . "','{$password}','1234','coucou','0783346912','1','1')"
);
echo "||||";
$pdo->exec(
  "INSERT INTO role_liaison 
        (id_role, id_user, created_at)
        VALUES 
        ('2','1', '2020-07-01 12:00:00')"
);
echo "||||]\n";
if (!$demo) {
  /**
   * Sans le Mode demo avec donné 
   */
} else {
  /**
   * Mode demo avec donné 
   */

  echo "Mode DEMO\n";

  /**
   * remplissage des tables
   */


  $faker = Faker\Factory::create('fr_FR');


  echo "[||||";
  $password = password_hash("testtest", PASSWORD_BCRYPT);

  $pdo->exec(
    "INSERT INTO user 
        (nom, prenom, mail, password, pin, token, tel, activate, verify)
        VALUES 
        ('admin','admin','test@test.fr','{$password}','1234','coucou','0783346912','1','1')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO role_liaison 
        (id_role, id_user, created_at)
        VALUES 
        ('1','2', '2020-07-01 12:00:00')"
  );
  echo "||||";

  $pdo->exec(
    "INSERT INTO user 
        (nom, prenom, mail, password, pin, token, tel, activate, verify)
        VALUES 
        ('admin','admin','test2@test.fr','{$password}','1234','coucou','0783346912','1','0')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO role_liaison 
        (id_role, id_user, created_at)
        VALUES 
        ('3','3', '2020-07-01 12:00:00')"
  );
  echo "||||";


  $pdo->exec(
    "INSERT INTO forfait 
        (nom, prix, duree)
        VALUES 
        ('test1','25','25')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO forfait 
        (nom, prix, duree)
        VALUES 
        ('test2','50','50')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO forfait 
        (nom, prix, duree)
        VALUES 
        ('test3','100','100')"
  );

  echo "||||";
  $pdo->exec(
    "INSERT INTO forfait_log 
        (id_user, id_forfait, created_at)
        VALUES 
        ('1','3','2020-05-22')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO forfait_log 
        (id_user, id_forfait, created_at)
        VALUES 
        ('1','1','2020-03-20')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO forfait_log 
        (id_user, id_forfait, created_at)
        VALUES 
        ('1','1','2020-01-30')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-01-30 09:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-01-30 10:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-02-30 09:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-02-30 10:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-07-28 09:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-07-28 10:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-07-28 11:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-07-29 04:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-07-29 05:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO heures 
        (id_user, created_at)
        VALUES 
        ('1','2020-07-29 07:00:00')"
  );

  echo "||||";
  $pdo->exec(
    "INSERT INTO compta_ligne 
        (`description`, `credit`,`debit`,`date`, created_at)
        VALUES 
        ('test','0', '12','2020-07-29 07:00:00','2020-07-29 07:00:00')"
  );
  echo "||||";
  $pdo->exec(
    "INSERT INTO compta_ligne 
        (`description`, `credit`,`debit`, `date`, created_at)
        VALUES 
        ('test2', '13', '0','2020-07-29 07:00:00','2020-07-29 07:00:00')"
  );



  echo "||||]\n";
}
