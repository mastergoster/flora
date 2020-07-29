<?php
$basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';

$app = App\App::getInstance();
$app->setStartTime();
$app::load();

$app->getRouter($basePath)
    ->get('/blog', 'Post#all', 'home')
    ->get('/tactile', 'Display#tactile', 'tactile')
    ->get('/boutique/panier', 'cart#index', 'cart')
    ->get('/categories', 'Category#all', 'categories')
    ->get('/category/[*:slug]-[i:id]', 'Category#show', 'category')
    ->get('/article/[*:slug]-[i:id]', 'post#show', 'post')
    ->get('/', 'Shop#index', 'shopIndex')
    ->get('/boutique', 'Shop#all', 'shopAll')
    ->get('/boutique/commande', 'Shop#purchaseOrder', 'shopPurchaseOrder')

    ->match('/inscription', 'Users#subscribe', 'usersSubscribe')

    ->get('/login', 'Users#login', 'usersLogin')
    ->get('/user/logout', 'users#logout', 'userLogout')
    ->get('/user/profile', 'users#profile', 'userProfile')
    ->get('/contact', 'Shop#contact', 'shopContact')
    //POSTS URLS

    ->post('/user/admin/newline', 'Users#ajaxNewUserLine', 'post_AjaxNewUserLine')
    ->post('/display/admin/new/line', 'Display#ajaxDisplayNewLine', 'post_ajaxDisplayNewLine')
    ->post('/login', 'Users#login', 'post_usersLogin')
    ->post('/user/updateUser', 'users#updateUser', 'post_updateUser')
    ->post('/user/changePassword', 'users#changePassword', 'post_updateChangePassword')
    ->post('/boutique/commande', 'Shop#purchaseOrder', 'post_PurchaseOrder')
    ->run();
