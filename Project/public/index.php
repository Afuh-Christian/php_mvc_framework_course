

<?php

use app\controllers\SiteController;
use app\core\Application;  

require_once __DIR__.'\..\vendor\autoload.php';

$app = new Application(dirname(__DIR__)); 

// $app->router->get('/',  [new SiteController(),'home']);
$app->router->get('/',  [SiteController::class,'home']);



$app->router->get('/contact', 'contact');

// [class_instance or static_class , action_name]
// $app->router->post('/contact' , [new SiteController(),'contact']);
$app->router->post('/contact' , [SiteController::class,'handleContact']);

$app->run(); 



# Creating the 