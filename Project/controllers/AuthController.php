<?php 

namespace app\controllers;
use app\controllers\Controller;

class AuthController extends Controller {
    public function login() {
        // -----------------------------------
        $this->setLayout('auth');
        // -----------------------------------
        return $this->render('login');
    }
    public function logout() {}
    public function register($request) {
        if($request->isPost()){
            return "Handle Submitted data";
        }
        // -----------------------------------
        $this->setLayout('auth');
        // -----------------------------------
        return $this->render('register');
    } 
 
}