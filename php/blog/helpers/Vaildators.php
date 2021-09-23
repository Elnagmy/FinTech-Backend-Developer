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
    

    function validatePostCreate($request){
        $errors = [];
        return $errors;
    }


    function checkIfUserCanEditPost($post)
{
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!isset($_SESSION['user']))
        return false;
    return $_SESSION['user']->type == 1 || $_SESSION['user']->id == $post->author->id;
}
   

    function checkIfUserisAdmin()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user']))
            return false;
        return $_SESSION['user']->type == 1 ;
    }

    function getUserIdFromSession()
    {
        if (session_status() != PHP_SESSION_ACTIVE) session_start();
        if (isset($_SESSION['user'])) return $_SESSION['user']->id;
        return null;
    }
    function sentisizeUserInputRegistration($request) {
            $request['name']=filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
            $request['username'] =filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $request['email'] =filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
            $request['phone'] =filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
            return $request;
    }


    function isAutorizedToDeleteComment ($comment , $post){
        if($this->checkIfUserisAdmin()) return true;
        if($post!=null && $this->getUserIdFromSession() ==  $comment->author->id) return true;
        if($post!=null && $this->getUserIdFromSession() ==  $post->author->id) return true;
        return false;
    }

}