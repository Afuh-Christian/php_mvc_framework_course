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