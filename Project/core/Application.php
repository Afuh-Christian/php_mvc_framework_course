<?php 
namespace app\core;
// use app\core\Router;

class Application
{
    public Router $router; 
    public Request $request; 
    public static string $ROOT_DIR;
    public function __construct($rootPath)
    {
      self::$ROOT_DIR = $rootPath; // single instance of app so constructor will not be called twice . 
      $this->request = new Request();
      $this->router = new Router($this->request);
    }

    public function get($path , $callback){
      $this -> router -> get($path , $callback);
    }

    public function run (){
     echo  $this->router->resolve();
    }
  }
?>

