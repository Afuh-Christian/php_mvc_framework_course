# Authentication ..(Just creating the pages and controllers )    1:11:26 

#### Creating helper functions to check if it's a ```post``` or  ```get```. 

```php
// core/Request.php . 

<?php 

namespace app\core ;

class Request {
    public function getPath(){
    $path = $_SERVER['REQUEST_URI'] ?? '/'; 
    $position = strpos($path, '?');
    return !$position? $path : substr($path , 0 , $position); 
    }
    public function method(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    
    public function getBody(){
        $body = []; 
        if($this->method() === 'get'){
            foreach($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET , $key , FILTER_SANITIZE_SPECIAL_CHARS); // Removes invalid characters and place in the $body . 
            }
        }
        if($this->method() === 'post'){
            foreach($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST , $key , FILTER_SANITIZE_SPECIAL_CHARS); /
            }
        }
        return $body;
    }

//----------------------------------------------------------
 public function isGet(){
   return $this->method() === 'get';
 }
 public function isPost(){
   return $this->method() === 'post';
 }
//----------------------------------------------------------
}
```


#### Creating the ```AuthController``` class .


```php

// controllers/AuthController.php

<?php 

namespace app\controllers;
use app\controllers\Controller;

class AuthController extends Controller {
    public function login() {
        return $this->render('login');
    }
    public function logout() {}
    public function register($request) {
        if($request->isPost()){
            return "Handle Submitted data";
        }
        return $this->render('register');
    } 
 
}

```


####  Creating the routes to these ```AuthControllers```

- I made a slide Adjustment , so that the Controllers are instantiated only once in the lifetime of the application . 


```php

// public/index.php 


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


// --------------------------------------------------------------
$app->router->get('/login' , [$authController,'login']);
$app->router->post('/login' , [$authController,'login']);
$app->router->get('/register' , [$authController,'register']);
$app->router->post('/register' , [$authController,'register']);
// --------------------------------------------------------------

$app->run(); 


```


