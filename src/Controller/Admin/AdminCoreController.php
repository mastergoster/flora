<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;

class AdminCoreController extends Controller
{

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile');
        }
    }

    public function update()
    {

        if (\getenv("ENV_DEV")) {
            return dd("mode dev impossible de faire ceci");
        }
        chdir("/var/www");
        putenv("COMPOSER_HOME=/var/www/.config/composer");

        if (!file_exists("composer.phar")) {


            $source = "https://getcomposer.org/installer";
            $destination = "/var/www/composer-setup.php";

            $data = file_get_contents($source);
            $file = fopen($destination, "w+");
            fputs($file, $data);
            fclose($file);

            echo `php composer-setup.php`;
            echo `php -r "unlink('composer-setup.php');"`;
        }

        exec("sudo git pull", $git);
        $composer = `php composer.phar update -v --no-dev -o 2>&1`;
        $composer2 = [];
        for ($i = 2; $i < strlen($composer); $i++) {
            $composer2[] = $composer[$i];
        }
        \var_dump($composer2);
        exec("vendor/bin/phinx migrate", $phinx);
        exec("sudo chown -R www-data:www-data *", $chmod, $codechmod);
        return $this->render("admin/update", ["itemss" => [
            "git" => $git,
            "composer" => $composer2,
            "phinx" => $phinx,
            "codechmod" => $codechmod
        ]]);
    }
}
