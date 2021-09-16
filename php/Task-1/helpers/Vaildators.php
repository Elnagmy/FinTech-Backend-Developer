<?php

class Vaildator {

    public function validateEmptyString($request, $name, &$errors) {
        
       if (!isset($request[$name]) || empty($request[$name])) {
            $errors[$name] = 'Please enter a valid ' . $name;
       }
    }

    public function validateEmail($request, $name, &$errors){
        if (!isset($request[$name]) || empty($request[$name]) || !filter_var($request[$name], FILTER_VALIDATE_EMAIL)) {
            $errors[$name] = 'Please enter a valid ' . $name;
        }
    }

   public function vaildateRegistration($request ){
        $errors = [];
        $this->validateEmptyString($request, 'name', $errors);
        $this->validateEmptyString($request, 'username', $errors);
        $this->validateEmptyString($request, 'password', $errors);
        $this->validateEmptyString($request, 'confirm_password', $errors);
        if (isset($request['password']) && isset($request['confirm_password'])
        && $request['password'] != $request['confirm_password']) {
            $errors['password_confirm'] = 'Password confirmation does not match';
        }
        $this->validateEmail($request, 'email', $errors);

        return $errors;
    }

   

}