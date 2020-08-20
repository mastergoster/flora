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
        // if (\getenv("ENV_DEV")) {
        //     return dd("mode dev impossible de faire ceci");
        // }
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
        echo shell_exec("vendor/bin/phinx migrate 2>&1");
        echo "<br>";
        echo shell_exec("sudo composer update 2>&1");
        echo "<br>";

        echo shell_exec("sudo git pull 2>&1");

        dd();
    }
}