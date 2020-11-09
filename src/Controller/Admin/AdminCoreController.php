<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;

class AdminCoreController extends Controller
{
    private $md5Hash;

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile');
        }
        chdir("/var/www");
    }

    public function update()
    {
        if (\getenv("ENV_DEV")) {
            return $this->render(
                "admin/update",
                ["itemss" => ["erreur" => ["mode dev impossible de faire ceci"]]]
            );
        }

        $this->md5("composer.json");
        $this->md5("phinx");
        $request = $this->request()->query;
        $git = explode("\n", `sudo git pull origin prod 2>&1`);


        if (!$this->md5("composer.json", true) || $request->get("composer") == "force") {
            putenv("COMPOSER_HOME=/var/www/.config/composer");
            $composer = explode("\n", `composer update -v --no-dev -o 2>&1`);
        } else {
            $composer = ["non lancé"];
        }

        if (!$this->md5("phinx", true) || $request->get("phinx") == "force") {
            $phinx = explode("\n", `vendor/bin/phinx migrate 2>&1`);
        } else {
            $phinx = ["non lancé"];
        }

        `sudo chown -R www-data:www-data *`;

        return $this->render("admin/update", ["itemss" => [
            "git" => $git,
            "composer" => $composer,
            "phinx" => $phinx
        ]]);
    }


    private function md5Hash(string $folder, string $item)
    {
        if (is_dir($item)) {
            $this->md5Folder($folder, $item);
        } else {
            $this->md5Hash[$folder][$item] = md5_file($item);
        }
    }

    private function md5Folder(string $folder, string $item)
    {
        foreach (scandir($item) as $value) {
            if ($value[0] != ".") {
                $this->md5Hash($folder, $item . "/" . $value);
            }
        }
    }

    private function md5(string $folder, bool $verify = false)
    {
        if (!$verify) {
            return $this->md5Hash($folder, $folder);
        }
        $this->md5Hash("verify_" . $folder, $folder);
        return array_diff_assoc($this->md5Hash["verify_" . $folder], $this->md5Hash[$folder]) ? false : true;
    }
}
