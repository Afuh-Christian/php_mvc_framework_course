<?php 

namespace app\controllers;
use app\controllers\Controller;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller {
    public function login() {
        $this->setLayout('auth');
        return $this->render('login');
    }
    public function logout() {}
    public function register(Request $request) {

        $registerModel = new RegisterModel();
        if($request->isPost()){
        $registerModel->loadData($request->getBody());


        // ------------------------------------------------------
        if($registerModel->validate() && $registerModel->register()){
            return "Success";
        }
        echo "<pre>";
        var_dump($registerModel->errors);
        echo "</pre>";
        exit;
        // ------------and we also see the resulting error on the browser------------------------------------------


        return $this->render('register',['registerModel' => $registerModel ]);
        }



        $this->setLayout('auth');
        return $this->render('register' , ['registerModel' => $registerModel , ]);
    } 
 
}