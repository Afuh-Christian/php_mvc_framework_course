# Working with ```Modals``` , ```forms``` and ```validation```



### Validate the raw way 

```php
// controllers/AuthController.php 

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

        $errors = [];
        if($request->isPost()){
            // Getting the posted data from the view . 
            $firstname = $request->getBody()['firstname'];
            if(!$firstname){  
                $errors["firstname"] = "This is a Required Field";
            }
            //-------------------------------------------------
        }
        $this->setLayout('auth');
        // Pass the error in the  view ...
        $params = [
            'errors' => $errors , 
        ];



        return $this->render('register' , $params);
    } 
 
}
```
- This approach is tedious because we'll have to go through every entry manually 
- So we'll go ahead to use Models ....





### Create a modal 

In the auth contorller where we are handling the submitted data , we create a  registerModal . 
We'll use the function [property_exists](https://www.geeksforgeeks.org/php-property_exists-function/)

NB ```First create a RegisterModal class ```


```php 
// models/Model.php


<?php 
namespace app\models;
abstract class Model {

     // To dynamically set the properties of the Current Model we're using .
        public function loadData($data){
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
            $this->$key = $value;
            }
        }
    
    }
    public function saveData(){}
    public function validate(){}
}
```

```php 
<?php 
// models/RegisterModel.php

namespace app\models ; 

class RegisterModel extends Model {
    public string $firstname; 
    public string $lastname; 
    public string $email; 
    public string $password; 
    public string $ConfirmPassword; 

    public function register(){
        echo "Creating new user ";
    }
}
```
- Now we call the loadData function in the AuthController and register action .. 
```php 

//controllers/AuthController.php 


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

        $errors = [];
        $registerModel = new RegisterModel();

        if($request->isPost()){
        // ------------------------------------------------------------------
        $registerModel->loadData($request->getBody());
        // ------------------------------------------------------------------
        echo "<prev>";
        var_dump($registerModel);
        echo "</prev>";
        exit;
        }
        $this->setLayout('auth');
      



        return $this->render('register' , ['registerModel' => $registerModel , ]);
    } 
 
}

```

Now the values are dynamically set ... 