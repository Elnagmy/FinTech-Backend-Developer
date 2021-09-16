<?php
require_once ('config.php');
require_once  (ROOT_DIR.'\models\UserModel.php');
class UserControlers {

    public function addNewUser ( $user) {
        $userModel = new UserModel();
        $return = $userModel->insertNewUser( $user);
        return  $return;
    }


}