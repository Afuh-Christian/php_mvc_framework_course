# CONTROLLERS 

<br>


### To ```Render the page``` and ```Pass data``` from controller to the page . 
<hr>


```php 
// views/home.php

<h1>  
Welcome <?php echo $params["name"]; ?>
</h1>

```


```php
// controller/SiteController.php

<?php 

namespace app\controllers;

use app\core\Application;

class SiteController 
{
    public static function home(){

        $params = [
            "name" => "Catholic boy"
        ];

        return Application::$app->router->renderView('home', $params); 
    }

}
```


```PHP
<?php 
// core/Router.php


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
$method = $this->request->getMethod();
$callback = $this->routes[$method][$path] ?? false;

if($callback === false) {
    $this->response->setStatusCode(404);
    return $this->renderView("_404");
}

if(is_string($callback)){
    return $this->renderView($callback);
}

return call_user_func($callback);
}

// ------------ ADDEDED THE NEW PARAMETER $params
function renderView($view , $params = []){
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view , $params);
    return str_replace('{{content}}', $viewContent , $layoutContent);
}

protected function layoutContent(){
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/main.php";
    return ob_get_clean(); 
}
// ------------ ADDEDED THE NEW PARAMETER $params
protected function renderOnlyView($view , $params = []){
    // $var = $params
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php"; // include auto sees the $params and any other variable in this renderOnlyView method .
    return ob_get_clean();
}
}
?>
```



```php
// public/index.php 




<?php

use app\controllers\SiteController;
use app\core\Application;  

require_once __DIR__.'\..\vendor\autoload.php';

$app = new Application(dirname(__DIR__)); 

// ..................................................
$app->router->get('/',  [SiteController::class,'home']);
// $app->router->get('/',  [new SiteController(),'home']);
// ..................................................

$app->router->get('/contact', 'contact');

$app->run(); 

# Creating the 
```

#### NB : When accessing the the controllers : - 
- ```SiteController::class``` to access ```static actions```
- ```new SiteController()``` to access ```normal actions```



<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


### Create a base controller class to prevent repetition

```php
// controllers/Controller.php

<?php 

namespace app\controllers;
use app\core\Application;

class Controller {
    public function render($view , $params=[]){
        return Application::$app->router->renderView($view , $params); 
    }
}
```

```php
// controllers/SiteController.php

<?php 

namespace app\controllers;

class SiteController extends Controller
{
    public function home(){

        $params = [
            "name" => "Catholic boy"
        ];

        return $this->render('home' , $params);
    }

}
```

```NB```:   ```$this``` cannot be used by a static method since it has to do with the current instance . so in the index.php ... we will access the Controller we need using .. ```new SiteController()```

```php
// public/index.php



<?php

use app\controllers\SiteController;
use app\core\Application;  

require_once __DIR__.'\..\vendor\autoload.php';

$app = new Application(dirname(__DIR__)); 

//----------------------------------------------------------
$app->router->get('/',  [new SiteController(),'home']);
//----------------------------------------------------------

$app->run(); 



# Creating the 
```





<br><br><br><br><br><br><br><br><br><br><br>


# Solving the static issue .



```php
// controllers/Controller.php

<?php 

namespace app\controllers;
use app\core\Application;

class Controller {
// make the render method static .-------------------
    public static function render($view , $params=[]){
        return Application::$app->router->renderView($view , $params); 
    }
}
```

```php
// controllers/SiteController.php

<?php 

namespace app\controllers;

class SiteController extends Controller
{
    public static function home(){

        $params = [
            "name" => "Catholic boy"
        ];
//--------------------------------------------------
        return self::render('home' , $params);
//--------------------------------------------------
    }
}
```


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
$method = $this->request->getMethod();
$callback = $this->routes[$method][$path] ?? false;

if($callback === false) {
    $this->response->setStatusCode(404);
    return $this->renderView("_404");
}

if(is_string($callback)){
    return $this->renderView($callback);
}
// ---------------------------------------------
if(is_array($callback)){
    $callback[0] = new $callback[0]();
}
// ---------------------------------------------

return call_user_func($callback);
}

function renderView($view , $params = []){
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view , $params);
    return str_replace('{{content}}', $viewContent , $layoutContent);
}

protected function layoutContent(){
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/main.php";
    return ob_get_clean(); 
}

protected function renderOnlyView($view , $params = []){
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php"; 
    return ob_get_clean();
}
}
?>

```



```php
// public/index.php



<?php

use app\controllers\SiteController;
use app\core\Application;  

require_once __DIR__.'\..\vendor\autoload.php';

$app = new Application(dirname(__DIR__)); 

//----------------------------------------------------------
$app->router->get('/',  [SiteController::class,'home']);
//----------------------------------------------------------

$app->run(); 





```






<br><br><br><br><br><br><br><br><br><br><br>






