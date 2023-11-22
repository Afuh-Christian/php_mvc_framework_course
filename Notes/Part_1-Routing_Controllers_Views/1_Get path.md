# Working with path ... 

We wil be getting the path via the ```REQUEST_URI```


### ```core/Request.php```

```php

<?php 

namespace app\core ;

class Request {
    // ........................................
    public function getPath(){
    $path = $_SERVER['REQUEST_URI'] ?? '/'; 
    $position = strpos($path, '?');
    return !$position? $path : substr($path , 0 , $position); 
    }
 // ........................................
   public function getMethod(){

    
    }
}
```

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

// ........................................
 public function resolve(){
$path =  $this->request->getPath();
 echo '<pre>'; 
var_dump($path);
echo '</pre>'; 
exit;

} 
// ........................................

}

?>
```


### ```core/Application.php```

```php
<?php 
namespace app\core;

class Application
{
    public Router $router; 
    public Request $request; 
    public function __construct()
    {
//......................................
      $this->request = new Request();
      $this->router = new Router($this->request);
    //......................................
    }
    public function run (){
     $this->router->resolve();
    }
  }
?>


```