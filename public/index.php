<?php

use app\controllers\AuthController;
use app\core\Application;
use app\controllers\SiteController;

require_once __DIR__ .'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config =[
    'userClass' => \app\models\User::class,
    'db'=>[
        'dsn'=>$_ENV['DB_DSN'],
        'username'=>$_ENV['DB_USERNAME'],
        'password'=>$_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(dirname(__DIR__),$config);

/**
 * this is where we store the uri associated with their callback functions
 * in the routes array in the Route class
 */
$app->router->get('/',[SiteController::class,'home']);
$app->router->get('/contact',[SiteController::class,'contact']);
$app->router->post('/contact',[SiteController::class,'contact']);

$app->router->get('/login',[AuthController::class,'login']);
$app->router->post('/login',[AuthController::class,'login']);
$app->router->get('/register',[AuthController::class,'register']);
$app->router->post('/register',[AuthController::class,'register']);
$app->router->get('/logout',[AuthController::class,'logout']);
$app->router->get('/profile',[AuthController::class,'profile']);

/**
 * this is responsible for resolving the uri and serving the user with the
 * page asked for
 */
$app->run();