<?php

namespace App\Controller\Admin;

use \Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminCoreController extends Controller
{
    private $md5Hash;

    public function __construct()
    {
        if (!$this->security()->isAdmin()) {
            $this->redirect('userProfile')->send();
            exit();
        }
        chdir(\getenv("PATH_BASE"));
    }

    public function update(): Response
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
        putenv("BRANCH=" . \getenv("BRANCH"));
        $git = explode("\n", `git pull origin $(printenv BRANCH) 2>&1`);


        if (!$this->md5("composer.json", true) || $request->get("composer") == "force") {
            //putenv("COMPOSER_HOME=/var/www/.config/composer");
            $composer = explode("\n", `composer update -v --no-dev -o 2>&1`);
        } else {
            $composer = ["non lancé"];
        }

        if (!$this->md5("phinx", true) || $request->get("phinx") == "force") {
            $phinx = explode("\n", `vendor/bin/phinx migrate 2>&1`);
        } else {
            $phinx = ["non lancé"];
        }


        return $this->render("admin/update", ["itemss" => [
            "git" => $git,
            "composer" => $composer,
            "phinx" => $phinx
        ]]);
    }


    private function md5Hash(string $folder, string $item): void
    {
        if (is_dir($item)) {
            $this->md5Folder($folder, $item);
        } else {
            $this->md5Hash[$folder][$item] = md5_file($item);
        }
    }

    private function md5Folder(string $folder, string $item): void
    {
        foreach (scandir($item) as $value) {
            if ($value[0] != ".") {
                $this->md5Hash($folder, $item . "/" . $value);
            }
        }
    }

    private function md5(string $folder, bool $verify = false): bool
    {
        if (!$verify) {
            $this->md5Hash($folder, $folder);
            return true;
        }
        $this->md5Hash("verify_" . $folder, $folder);
        return array_diff_assoc($this->md5Hash["verify_" . $folder], $this->md5Hash[$folder]) ? false : true;
    }
}
