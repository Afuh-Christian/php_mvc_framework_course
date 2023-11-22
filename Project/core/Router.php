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

$path =  $this->request->getPath();
$method = $this->request->getMethod();
$callback = $this->routes[$method][$path] ?? false;

if(is_string($callback)){
    return $this->renderView($callback);
}

if($callback === false) {
    return "Not found";
}
return call_user_func($callback);

}

function renderView($view){
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view);
    return str_replace('{{content}}', $viewContent , $layoutContent);
}

protected function layoutContent(){
    ob_start();// starts the output caching . 
    include_once Application::$ROOT_DIR."/views/layouts/main.php";
    return ob_get_clean(); // returns the value and clears the buffer . 
}

protected function renderOnlyView($view){
    ob_start();// starts the output caching . 
    include_once Application::$ROOT_DIR."/views/$view.php";
    return ob_get_clean(); // returns the value and clears the buffer . 

}
}
?>