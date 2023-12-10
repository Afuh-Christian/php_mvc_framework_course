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

