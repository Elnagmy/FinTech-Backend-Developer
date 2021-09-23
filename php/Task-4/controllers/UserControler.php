<?php

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
        if ($loginUser->Status != 1) return "Blocked";
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

    function getUserIdFromSession()
{
    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if (isset($_SESSION['user'])) return $_SESSION['user']->id;
    return null;
}


function getallUsers($page_size, $page,  $q, $order_field, $order_by ) {

    if($order_field == null) $order_field= "name";
    if($order_by == null) $order_field= "asc";
    $userModel = new UserModel();
    return [
        'data' => $userModel->getallUsers($page_size, $page,  $q, $order_field, $order_by ),
        'count' => $userModel -> get_usersCount()
    ];
}


function blockUser ( $user_id){
    $userModel = new UserModel();
    return $userModel->blockUser ( $user_id);
}

function unBlockUser ( $user_id){
    $userModel = new UserModel();
    return $userModel->unBlockUser( $user_id);
    
}

function PromoteTOAdmin ( $user_id){
    $userModel = new UserModel();
    return $userModel->PromoteTOAdmin( $user_id);
}

function RevokeAdmin ( $user_id){
    $userModel = new UserModel();
    return $userModel->RevokeAdmin( $user_id);
    
}

function removeUser ( $user_id){
    $userModel = new UserModel();
    return $userModel->removeUser( $user_id);
    
}


function follow ( $following_id , $followers_id) {
    $userModel = new UserModel();
    return $userModel->follow ( $following_id , $followers_id);
    
}


function unFollow ( $following_id , $followers_id) {
    $userModel = new UserModel();
    return $userModel->unFollow ( $following_id , $followers_id);
    
}

function getUploadedImage($files){
   
    if ($files['Profile-image']['size'] == 0 ) return null;
    move_uploaded_file($files['Profile-image']['tmp_name'],ROOT_DIR.'/assets/images/'.$files['Profile-image']['name']);
    return '/assets/images/' . $files['Profile-image']['name'];
}





}