<?php 
namespace app\core;


// use app\core\Router;

class Application
{
    public Router $router; 
    public Request $request; 
    public Response $response; //
    public static Application $app; //
    public static string $ROOT_DIR;
    public function __construct($rootPath)
    {
      self::$ROOT_DIR = $rootPath; 
      $this->request = new Request();
      self::$app = $this; //
      $this->response = new Response();//
      $this->router = new Router($this->request , $this->response); // 
    }

    // public function get($path , $callback){
    //   $this -> router -> get($path , $callback);
    // }
    // public function post($path , $callback){
    //   $this -> router -> post($path , $callback);
    // }

    public function run (){
     echo  $this->router->resolve();
    }
  }
?>

