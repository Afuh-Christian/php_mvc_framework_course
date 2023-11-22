
# Creating the server  
This is done in the ```index.php``` file. 



```php 
// index.php

$app = new Application(); 
$router = new Router();

$router ->get('/', function(){
    return 'Hello World';
});

$app->userRouter($router)
$app->run(); 

```
#### Or use this method
```php 
// index.php

$app = new Application(); 

$app->$router ->get('/', function(){
    return 'Hello World';
});

$app->run(); 

```

For this we have to create two classes . 
- Application 
- Router 

So let's create a folder called  ```core``` which will contain . 
- All the classes of the ```core cms```  (Later we will make this core installable using composer)      

#### In the ```Core/``` we create . 
- Application class .
- Router class . 
```php 

<?php
// router class  
class Router 
{
 
}
?>
```

```php 
<?php
// Application class  
class Application 
{
    public Router $router; 
    public function __construct()
    {
      $this->router = new Router();
    }
}
?>
```

For this we need to do the following .. 
- index.php  , we need to ```require_once``` 
- core/Application.php , we need to 
```require Router```

This becomes stressfull when we have to do this for many routes because we will have to require every time  . 

# Composer (Downloaded separately)
This is a cli tool that facilitates our work .

Create project as a ```composer project```

```
composer init
```
Accept everything if it's ok with you .

What we are to  configure ```autoload``` in the ```composer.json```  
- The namespaces etc
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
            "name": "Afuh Christian Forkoum",
            "email": "afuhchristian10@gmail.com"
        }
    ],
    "require": {}
}

```
We changed the namespace to ```app``` .

```json
// NB always run composer update on the cli after updating the composer.json file .
composer update
```

A ```composer.lock``` has been generated .

## Now we specify the namespaces to all class that would be imported ..
`

```php 
<?php

namespace app\core; // 

// Application class  
class Application 
{
    public Router $router; 
    public function __construct()
    {
      $this->router = new Router();
    }
}
?>
```
```php 
<?php

namespace app\core; // 

// Router class  
class Router 
{

}
?>
```
Now we import all classes into the ```index.php``` file so that we won't need to be imported all the different classes independently .

```__DIR__``` = same directory 

```php
<?php    
require_once __DIR__.'\vendor\autoload.php';use app\core\Applicaiton; //

$app = new Application(); 

$app->$router ->get('/', function(){
    return 'Hello World';
});

$app->run(); 

?>
```



# Creaing get(for Route class ) and run(for Application class ) methods .

```php

<?php 
use app\core\Application;   
require_once __DIR__.'\vendor\autoload.php';

$app = new Application(); 

$app->$router ->get('/', function(){
    return 'Hello World';
});

$app->$router ->get('/Contact', function(){
    return 'Contact';
});

$app->run(); 


```
Now how do we decide which function to execute "/" , or "/Contact"

#### Router class .

- We initialize an empty array of routes 
- Then created an associative array from it . 
    - For every path , theres a corresponding callback . ```path => callback```
- Will have to do for all the ```httpMethods``` 
    - So it'll be a nested array [httpMethod]["path"=>"callback"]

So it'll be something like this ... 

```php 
<?php

namespace app\core; // 

// Router class  
class Router 
{
    protected array routes = [
        'get'=>[
            '/' => dosomething(),
            '/Contacts' => dosomething(),
        ] , 
        'post' => [
            "/Add" => addSomething()
        ]
    ]; 
    public function get($path , $callback){
        $this->routes['get'][$path] => $callback; // turning it into an associative array ..
    }

}
?>
```


Now we need to work out what the run() does .... 

```php 
<?php

namespace app\core; // 
use app\core\Router;

// Application class  
class Application 
{
    public Router $router; 
    public function __construct()
    {
      $this->router = new Router();
    } , 
    public function run () {
        $this->router ->resolve();
    }
}
?>
```

Now we need to create the resolve function in the ```Router class```  . 

```php
<?php 

namespace app\core;

class Router
{
 protected array $routes = [];
 public function get($path , $callback){
 $this->routes['get'][$path] = $callback;
 } 
 public function resolve(){
 // determine current url path & method
 //$_SERVER   superglobal server (contains all info we need .)
 
//  echo "Browser Data .";
echo '<pre>'; 
var_dump($_SERVER);
echo '</pre>'; 
exit;
// echo "Hello resolve"; 
} 
}
?>
```

#### ```$_SERVER``` Returns information about the server . Check at : 
  - [$_SERVER manual](https://www.php.net/manual/en/reserved.variables.server.php)
  - [w3schools $_SERVER](https://www.w3schools.com/php/php_superglobals_server.asp)

#### You can also got to [w3schools superglobals](https://www.w3schools.com/php/php_superglobals.asp) to get more information about the other superglobals like 
- $_GET
- $_POST
- $_GLOBALS
- $_REQUEST
- $_SERVER


 ```NB``` : ```PATH_INFO``` only shows in the 