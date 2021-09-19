<?php
require_once ('config.php');
require_once  (ROOT_DIR.'\models\UserModel.php');
class UserController {

    public function addNewUser ( $user) {
        $userModel = new UserModel();
        $return = $userModel->insertNewUser( $user);
        return  $return;
    }

    public function TryUserLogin ( $user , $password) {
        $userModel = new UserModel();
        $loginUser=  $userModel->TryUserLogin( $user , $password);
     
        if ( $loginUser != null ){
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['user'] = $loginUser;
            return $loginUser;
        }

    }

    function logOut(){
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_destroy();
    header('Location:' . BASE_URL . '/index.php');
    die();
    }

    function getActiveUsers($limtCount) {
        $userModel = new UserModel();
        $return = $userModel->getActiveUsers( $limtCount);
        return  $return;
    }



}