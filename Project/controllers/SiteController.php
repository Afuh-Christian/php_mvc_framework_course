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


    public static function handleContact(Request $request){
        
      $body = $request->getBody();
      echo '<pre>'; 
      var_dump($body);
      echo '</pre>';
    return ;
    }
}