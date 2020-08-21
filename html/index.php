<?php

$basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';
$app = App\App::getInstance();
$app->setStartTime();
$app::load();


$url = explode("?", $_SERVER["REQUEST_URI"]);
$url[0] = explode("/", $url[0]);
unset($url[0][0]);
if (end($url[0]) == "") {
    array_pop($url[0]);
}
$_SERVER["REQUEST_URI"] = "/" . join("/", $url[0]) . (isset($url[1]) ? "?" . $url[1] : "");

$app->getRouter($basePath)
    // ->get('/boutique/panier', 'cart#index', 'cart')
    // ->get('/categories', 'Category#all', 'categories')
    // ->get('/category/[*:slug]-[i:id]', 'Category#show', 'category')
    // ->get('/article/[*:slug]-[i:id]', 'post#show', 'post')
    //->get('/boutique', 'Shop#all', 'shopAll')
    //->get('/boutique/commande', 'Shop#purchaseOrder', 'shopPurchaseOrder')


    ->get('/admin/update', 'AdminCore#update', 'update')
    ->get('/validation/[*:slug]', 'users#validate', 'validate')
    ->get('/tactile', 'Display#tactile', 'tactile')
    ->get('/tv', 'Display#tv', 'tv')
    ->get('/', 'Shop#index', 'home')
    ->match('/events/[i:id]-[*:slug]/[*:email]?', 'Events#booking', 'eventsBooking')
    ->get('/events', 'Events#index', 'events')
    ->post('/admin/compta/add', 'AdminCompta#add', 'adminComptaadd')
    ->get('/admin/compta/ligne', 'AdminCompta#ligne', 'adminComptaligne')
    ->match('/admin/compta/ndf', 'AdminCompta#ndf', 'adminComptaNDF')
    ->get('/admin/compta', 'AdminCompta#index', 'adminCompta')


    ->match('/inscription', 'Users#subscribe', 'usersSubscribe')

    ->get('/login', 'Users#login', 'usersLogin')

    ->get('/admin', 'Admin#panel', 'adminPanel')
    ->match('/admin/users/[i:id]/modifheure', 'Admin#modifHeure', 'adminUserModifHeure')
    ->match('/admin/users/[i:id]', 'Admin#user', 'adminUser')
    ->match('/admin/events', 'AdminEvents#events', 'adminEvents')
    ->match('/admin/event/[i:id]?', 'AdminEvents#event', 'adminEventSingle')
    ->get('/admin/users', 'Admin#users', 'adminUsers')
    ->get('/admin/roles', 'Admin#roles', 'adminRoles')
    ->get('/admin/roles/[i:id]', 'Admin#role', 'adminRole')

    ->match('/admin/products', 'Admin#products', 'adminProducts')
    ->get('/admin/orders', 'Admin#orders', 'adminOrders')


    ->get('/user/logout', 'users#logout', 'userLogout')
    ->get('/user/profile', 'users#profile', 'userProfile')
    ->get('/activate', 'users#activatePage', 'activatePage')
    ->get('/contact', 'Shop#contact', 'contact')
    //POSTS URLS

    ->post('/user/admin/newline', 'Users#ajaxNewUserLine', 'post_AjaxNewUserLine')
    ->post('/display/admin/new/line', 'Display#ajaxDisplayNewLine', 'post_ajaxDisplayNewLine')
    ->post('/login', 'Users#login', 'post_usersLogin')
    //->post('/user/updateUser', 'users#updateUser', 'post_updateUser')
    //->post('/user/changePassword', 'users#changePassword', 'post_updateChangePassword')
    //->post('/boutique/commande', 'Shop#purchaseOrder', 'post_PurchaseOrder')

    ->run();
