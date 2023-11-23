
### To ```Submit form data``` or ```any other form of  data``` from  page to  controller  .  1:02:16
<hr> 


It's always advisable to filter the data from the ```$_POST``` that has been received cause you can pick up malicious software .

- This is usually done in one place . 

```php
// core/Request.php 



<?php 

namespace app\core ;

class Request {
    public function getPath(){
    $path = $_SERVER['REQUEST_URI'] ?? '/'; 
    $position = strpos($path, '?');
    return !$position? $path : substr($path , 0 , $position); 
    }
    public function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

//----------------------------------------------------------
    public function getBody(){
        $body = []; 
        if($this->getMethod() === 'get'){
            foreach($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET , $key , FILTER_SANITIZE_SPECIAL_CHARS); // Removes invalid characters and place in the $body . 
            }
        }
        if($this->getMethod() === 'post'){
            foreach($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST , $key , FILTER_SANITIZE_SPECIAL_CHARS); // Removes invalid characters and place in the $body . 
            }
        }
        return $body;
    }
//----------------------------------------------------------
}
```


- Always specify the ```name``` props on your ```<input/>```

```html 
<!-- views/contact.php -->

<h1>Contact</h1>

<form method="post" action="">
  <div class="form-group">
    <label >Subject</label>
    <input type="text" name="subject" class="form-control" >
  </div>
  <div class="form-group">
    <label >Email</label>
    <input type="text"  name="email" class="form-control" >
  </div>
  <div class="form-group">
    <label >Body</label>
    <input type="text"  name="body" class="form-control" >
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
```


- The SiteController now receives the filtered data .

```php

<?php 

namespace app\controllers;

use app\core\Application;

class SiteController extends Controller
{
    public static function home(){

        $params = [
            "name" => "Catholic boy"
        ];

        return self::render('home' , $params);
    }

// ------------------------------------------------------
    public static function handleContact(){
      $body = Application::$app->request->getBody();
    //   $body = self::body();
      echo '<pre>'; 
      var_dump($body);
      echo '</pre>';
    return var_dump($body);
    }
// ------------------------------------------------------
}

```
<br><br><br><br><br><br><br>


Let's simplify ```Application::$app->request->getBody()```

##### ```Method I```

```php
// controllers/Controller.php


<?php 
namespace app\controllers;
use app\core\Application;

class Controller {
    public static function render($view , $params=[]){
        return Application::$app->router->renderView($view , $params); 
    }
//-------------------------------------------------
    public static function body(){
        return Application::$app->request->getBody(); 
    }
//-------------------------------------------------
}

```


```php
// controllers/SiteController.php 


<?php 

namespace app\controllers;

use app\core\Application;

class SiteController extends Controller
{
    public static function home(){

        $params = [
            "name" => "Catholic boy"
        ];

        return self::render('home' , $params);
    }


    public static function handleContact(){
// -----------------------------------------
      $body = self::body();
// -----------------------------------------
      echo '<pre>'; 
      var_dump($body);
      echo '</pre>';
    return ;
    }
}
```


<br><br><br>



##### ```Method II```

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
if(is_array($callback)){
    $callback[0] = new $callback[0]();
}
// ----------Add new parameter in the call_user_func which will be $this->request---------------------------------------------
return call_user_func($callback , $this->request);
// -------------------------------------------------------
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
    include_once Application::$ROOT_DIR."/views/$view.php"; // include auto sees the $params ..
    return ob_get_clean();
}
}
?>
```

```php
// controllers/SiteController.php



<?php 

namespace app\controllers;

use app\core\Application;
use app\core\Request;

class SiteController extends Controller
{
    public static function home(){

        $params = [
            "name" => "Catholic boy"
        ];

        return self::render('home' , $params);
    }

// ----------------------------------------------------
    public static function handleContact(Request $request){
      $body = $request->getBody();
// ----------------------------------------------------
      echo '<pre>';
      var_dump($body);
      echo '</pre>';
    return ;
    }
}


```