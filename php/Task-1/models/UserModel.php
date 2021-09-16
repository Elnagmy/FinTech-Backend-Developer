<?php
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
class UserModel extends Model{
    
    function insertNewUser( $user){
        $databaseConnection = new DatabaseConnection ();
        $conn = $databaseConnection-> get_connection();

        $result =[] ; 
        if ($conn) {
            $SQL = "INSERT INTO users (id,name,username,password,email,phone) values (null,'" . $user->name . "','" . $user->userName . "',md5('" . $user->password . "'),'" . $user->email . "','" . $user->phone . "')";
            if (mysqli_query($conn, $SQL)) {
              $last_id = mysqli_insert_id($conn);
              $user->id=  $last_id;
              $result ["success"] = $user ; 
              return $result;
            }else {
            $result ["error"] = mysqli_error($conn);
            return $result;
        }
    }else {
        $result ["error"] = "Internal server Error";
        return $result;
    }
}

}