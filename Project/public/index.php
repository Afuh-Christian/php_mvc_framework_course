

<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;  

require_once __DIR__.'\..\vendor\autoload.php';

$app = new Application(dirname(__DIR__)); 

$siteController = $siteController ?? new SiteController();
$authController = $authController ?? new AuthController();

$app->router->get('/',  [$siteController,'home']);
$app->router->get('/contact', 'contact');
$app->router->post('/contact' , [$siteController,'handleContact']);



$app->router->get('/login' , [$authController,'login']);
$app->router->post('/login' , [$authController,'login']);
$app->router->get('/register' , [$authController,'register']);
$app->router->post('/register' , [$authController,'register']);

$app->run(); 



# Creating the 