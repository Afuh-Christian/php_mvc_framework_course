##  Implementing validation 

- Create ```rules``` in the ```Model``` class .
- Create the various  ```REQUIRED``` ``` properties needed``` 
- Implement the rules in one of the ```child``` ```models```  
- Create the ```validation``` function in the  ```model``` .other helper functions like 
    - ```addError```
    - ```errorMessages``` 

```php 
// models/Model 

<?php 
namespace app\models;
abstract class Model {

    // ------------------------------------------------------
    public const RULE_REQUIRED = 'required'; 
    public const RULE_EMAIL = 'email'; 
    public const RULE_MAX  = 'max'; 
    public const RULE_MIN  = 'min'; 
    public const RULE_MATCH = 'match'; 
    public const RULE_UNIQUE = 'unique'; 

  // ------------------------------------------------------

    public array $errors = []; 

    public function loadData($data){
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
            $this->$key = $value;
            }
        }
    }
    abstract public function rules(): array ; 
    public function saveData(){}



  
// --------------------------------------------------------------

    // To dynamically allocate the rules to their different  fields
    public function validate(){
        foreach($this->rules() as $attribute => $rules){ // each attribute has multiple rules ... 
            $value = $this->{$attribute}; 
            foreach($rules as $rule){
                $ruleName = $rule;
                if(!is_string($rule)){
                    $ruleName = $rule[0];
                    var_dump($rule);
                }
                if(($ruleName === self::RULE_REQUIRED && !$value)){
                    $this->addError($attribute , self::RULE_REQUIRED);

                }

            }
           
        }

        return empty($this->errors); // if the array = [] // returns true . (No errors ..)
    }

    public function addError(string $attribute , string $rule){ 
        $message = $this->errorMessages()[$rule] ?? ''; 
        $this->errors[$attribute][] = $message ;
    }
    public function errorMessages(){
        return [
            self::RULE_REQUIRED => "This field is required " , 
            self::RULE_EMAIL => "This field must be valid email address " , 
            self::RULE_MAX => "Max length of this field must be {max}" , 
            self::RULE_MIN => "Min length of this field must be {min}" , 
            self::RULE_MATCH => "This field must be the same as {match} " , 
            self::RULE_UNIQUE => "This field must be unique " , 
        ];
    }

    // --------------------------------------------------------------
}


```


- When we implement the rules in one of the ```child``` ```models``` 
- What happens in the ```model``` class in the ```validate()``` depends on the ```rules()``` being ```implemented``` in a ```child class``` .

```php 
// models/RegisterModel 

<?php 

namespace app\models ; 

class RegisterModel extends Model {
    public string $firstname; 
    public string $lastname; 
    public string $email; 
    public string $password; 
    public string $ConfirmPassword; 

      // ------------------------------------------------------
    public function rules(): array {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED , self::RULE_EMAIL] ,
            'password' => [self::RULE_REQUIRED , [self::RULE_MIN , 'min'=> 8] ,[self::RULE_MAX, 'max'=> 24] , ],
            'ConfirmPassword' => [self::RULE_REQUIRED , [self::RULE_MATCH , 'match'=>'password']],
        ];
    }
      // ------------------------------------------------------
    public function register(){
        echo "Creating new user ";
    }
}


```

#### Now implement the ```validate``` in the ```AuthController``` on the data received


```php 
// controller/AuthController 


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


```
##### Getting the validation error array on the console ... 
##### 00:23:19.518

