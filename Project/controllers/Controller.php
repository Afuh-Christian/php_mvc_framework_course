<?php 

namespace app\controllers;
use app\core\Application;

class Controller {
    public static function render($view , $params=[]){
        return Application::$app->router->renderView($view , $params); 
    }
    public static function body(){
        return Application::$app->request->getBody(); 
    }
}