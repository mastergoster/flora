<?php

use App\RouteConfig;

$basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$app = App\App::getInstance();
$app->setStartTime();
$app::load();

if (strpos($app->getConfig("urlsite"), "http") === 0) {
    if ($_SERVER['HTTPS'] != 'on') {
        $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $url, true, 301);
        exit();
    }
}

$url = explode("?", (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : ""));
$url[0] = explode("/", $url[0]);
unset($url[0][0]);
if (end($url[0]) == "") {
    array_pop($url[0]);
}
$_SERVER["REQUEST_URI"] = "/" . join("/", $url[0]) . (isset($url[1]) ? "?" . $url[1] : "");

$app->getRouter($basePath)->addConfig(new RouteConfig())->run();
