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
        exec("sudo composer update --no-dev -o", $composer, $codecomposer);
        exec("vendor/bin/phinx migrate", $phinx);
        exec("sudo chown -R www-data:www-data *", $chmod, $codechmod);
        return $this->render("admin/update", ["item" => [
            'git' => $git,
            "codecomposer" => $codecomposer,
            "phinx" => $phinx,
            "codechmod" => $codechmod
        ]]);
    }
}
