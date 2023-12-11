# Getting the Submitted data  

## Implementing the second layout .
- when user log's in or registers , another layout should display .

#### We create a two props in the ```Controller```


 ```php 
// controllers/Controller




<?php 

namespace app\controllers;
use app\core\Application;

class Controller {
    // ----------------------------------
    public string $layout = 'main' ; 
    public function setLayout($layout){
        $this->layout = $layout;
    }
    // ----------------------------------
    public function render($view , $params=[]){
        return Application::$app->router->renderView($view , $params); 
    }
    public function body(){
        return Application::$app->request->getBody();  
    }
}

```


### Create an instantiate a ```controller``` in the ```Application``` class 


```php
<?php 
namespace app\core;

use app\controllers\Controller;

// use app\core\Router;

class Application
{
    public Router $router; 
    public Request $request; 
    public Response $response;
    public static Application $app;
    public static string $ROOT_DIR;
        //----------------------------------------------
    public Controller $controller;
        //----------------------------------------------
    public function __construct($rootPath)
    {
      self::$ROOT_DIR = $rootPath; 
      $this->request = new Request();
      self::$app = $this; 
        //----------------------------------------------
      $this->controller = new Controller();
        //----------------------------------------------

      $this->response = new Response();
      $this->router = new Router($this->request , $this->response);  
    }



      public function getController(){
        return $this->controller; 
      }
      public function setController(Controller $controller): void {
        $this->controller = $controller;
      }


    public function run (){
     echo  $this->router->resolve();
    }
  }
?>


```


### Get the ```Controller``` instance created  and pass the ```layout``` prop to the ```layoutContent``` to set the layout of the page . 


```php 
// core/Router.php 


<?php 

namespace app\core;

class Router
{
    public Request $request;
    public Response $response;
    public function __construct(Request $request , Response $response){
        $this->request = $request;
        $this->response = $response;
    }
 protected array $routes = [];
 public function get($path , $callback){
 $this->routes['get'][$path] = $callback;
 } 

 public function post($path , $callback){
 $this->routes['post'][$path] = $callback;
 } 

 public function resolve(){

$path =  $this->request->getPath();
$method = $this->request->method();
$callback = $this->routes[$method][$path] ?? false;

if($callback === false) {
    $this->response->setStatusCode(404);
    return $this->renderView("_404");
}

if(is_string($callback)){
    return $this->renderView($callback);
}
if(is_array($callback)){
        //-----------------------
        Application::$app->controller = new $callback[0](); // Instance of controller created in the router .
        $callback[0] = Application::$app->controller;
        //-----------------------
}

return call_user_func($callback , $this->request);
}

function renderView($view , $params = []){
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view , $params);
    return str_replace('{{content}}', $viewContent , $layoutContent);
}

protected function layoutContent(){
    //----------------------------------------
    $layout = Application::$app->controller->layout; 
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
    return ob_get_clean(); 
   //----------------------------------------

}

protected function renderOnlyView($view , $params = []){
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    return ob_get_clean();
}}
?>

```


### Now Call an implement the ```setLayout()``` function from the ```Controller``` inside the ```AuthController``` class in the ```login``` and ```register``` actions 


```php

// controllers/AuthController 
<?php 

namespace app\controllers;
use app\controllers\Controller;

class AuthController extends Controller {
    public function login() {
        // -----------------------------------
        $this->setLayout('auth');
        // -----------------------------------
        return $this->render('login');
    }
    public function logout() {}
    public function register($request) {
        if($request->isPost()){
            return "Handle Submitted data";
        }
        // -----------------------------------
        $this->setLayout('auth');
        // -----------------------------------
        return $this->render('register');
    } 
 
}
```