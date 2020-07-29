#!/usr/bin/env php
<?php


declare(strict_types=1);
require_once("config.php");
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



echo "||||||||||||]
";

/**
 * remplissage des tables
 */


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

  $password = password_hash($passwordadmin, PASSWORD_BCRYPT);

  $pdo->exec(
    "INSERT INTO user 
        (nom, prenom, mail, password, pin, token, tel, activate, verify)
        VALUES 
        ('admin','admin','{$usernameadmin}','{$password}','1234','coucou','0783346912','1','1')"
  );
  echo "||||";

  $password = password_hash("test", PASSWORD_BCRYPT);

  $pdo->exec(
    "INSERT INTO user 
        (nom, prenom, mail, password, pin, token, tel, activate, verify)
        VALUES 
        ('admin','admin','test@test.fr','{$password}','1234','coucou','0783346912','1','1')"
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



  echo "||||]\n";
}
