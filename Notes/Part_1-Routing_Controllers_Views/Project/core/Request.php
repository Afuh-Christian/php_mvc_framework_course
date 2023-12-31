<?php 

namespace app\core ;

class Request {
    public function getPath(){
    $path = $_SERVER['REQUEST_URI'] ?? '/'; 
    $position = strpos($path, '?');
    return !$position? $path : substr($path , 0 , $position); 
    }
    public function method(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    

//----------------------------------------------------------
    public function getBody(){
        $body = []; 
        if($this->method() === 'get'){
            foreach($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET , $key , FILTER_SANITIZE_SPECIAL_CHARS); // Removes invalid characters and place in the $body . 
            }
        }
        if($this->method() === 'post'){
            foreach($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST , $key , FILTER_SANITIZE_SPECIAL_CHARS); // Removes invalid characters and place in the $body . 
            }
        }
        return $body;
    }
//----------------------------------------------------------


 public function isGet(){
   return $this->method() === 'get' ; 
 }

 public function isPost(){
   return $this->method() === 'post' ; 
 }


}