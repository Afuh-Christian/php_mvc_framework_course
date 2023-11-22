

<?php 
use app\core\Application;  

require_once __DIR__.'\..\vendor\autoload.php';

$app = new Application(dirname(__DIR__)); 

$app->get('/', 'home');

$app->get('/Contact', 'contact');

$app->run(); 



# Creating the 