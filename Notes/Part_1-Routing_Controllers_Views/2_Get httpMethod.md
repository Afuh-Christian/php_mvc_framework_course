# Working with method ... Basic Routing ..

We wil be getting the method via the ```REQUEST_METHOD``` 



### ```core/Request.php```

```php
<?php 

namespace app\core ;

class Request {
    public function getPath(){
    $path = $_SERVER['REQUEST_URI'] ?? '/'; 
    $position = strpos($path, '?');
    return !$position? $path : substr($path , 0 , $position); 
    }
    // .............................................
    public function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    // .............................................

}
```

We'll use the [call_user_func](https://www.geeksforgeeks.org/php-call_user_func-function/) to return the intended values for the route .. 
    "/" = "Hello world"
    "/Contact" = "Contact"

### ```core/Router.php```

```php
<?php 

namespace app\core;

class Router
{
    public Request $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
 protected array $routes = [];
 public function get($path , $callback){
 $this->routes['get'][$path] = $callback;
 } 
 public function resolve(){
// ....................................................
$path =  $this->request->getPath();
$method = $this->request->getMethod();
$callback = $this->routes[$method][$path] ?? false;
if($callback === false) {
    echo "Not found"; 
    exit;
}
echo call_user_func($callback);
// ....................................................
} 
}
?>
```


### ```core/Application.php```

```php
<?php 
namespace app\core;
// use app\core\Router;

class Application
{
    public Router $router; 
    public Request $request; 
    public function __construct()
    {
      $this->request = new Request();
      $this->router = new Router($this->request);
    }

    public function get($path , $callback){
      $this -> router -> get($path , $callback);
    }

    public function run (){
     $this->router->resolve();
    }
  }
?>





```



### ```core/Index.php```

```php


<?php 
use app\core\Application;  

require_once __DIR__.'\vendor\autoload.php';

$app = new Application(); 
// ............................................
$app->get('/', function(){
    return 'Hello World';
});

$app->get('/Contact', function(){
    return 'Contact';
});
// ............................................

$app->run(); 


```


###  ```NB``` if you type http://localhost:8000/composer.json it will display the following on your browser ..

```json
{
    "name": "christian/project",
    "autoload": {
        "psr-4": {
            "app\\": "src/"
        }
    },
    "authors": [
        {
            "name":"Afuh Christian Forkoum",
            "email":"afuhchristian10@gmail.com"
        }
    ],
    "require": {}
}
```
- Sensitive files should not be accessible from the browser 
- The index.php and ```composer.json``` should not be in the same dir . 
    - the ```index.php``` should be in another folder called ```public``` .
    - Now you'll start the ```server``` from that public dir.
    - We have to rearrange the ```imports``` in the ```index.php```.

```php
// index.php file .. 

<?php 
use app\core\Application;  

// ........................
require_once __DIR__.'\..\vendor\autoload.php';
// ........................

$app = new Application(); 

$app->get('/', function(){
    return 'Hello World';
});

$app->get('/Contact', function(){
    return 'Contact';
});

$app->run(); 

```

To start server .

```
> cd puplic 

> php -S localhost:8000 

```