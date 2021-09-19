<?php
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
require_once  (ROOT_DIR.'\models\Model.php');
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


    public function TryUserLogin ( $user_nameOrEmail , $password) {
        $values = [$user_nameOrEmail,$password];
        $types = 'ss';
        $sql = "SELECT * FROM users WHERE username=? and password = md5(?) limit 1;";
        $result = parent::getRow($sql , $types , $values); 
        if (  $result !=null){
            $loginUser = new User ($result["id"] , $result["name"] ,$result["username"], $result["password"],$result["email"],$result["phone"]);
            $loginUser->type = $result["type"];
            return $loginUser;
        }else {
            return null;
        }
      
    }


    function getActiveUsers($limtCount) {
        $SQL = "select u.id as user_id, u.name user_name , count(*) as postCnt from users u 
                inner join Posts p on p.user_id = u.id
                GROUP by p.user_id
                order by postCnt desc LIMIT ".$limtCount;
                $result = parent::getRows($SQL);
                $users = [];
                foreach ($result as $row){
                    $user= new User($row['user_id'] , $row['user_name'],null,null,null,null);
                    $user->postsCount = $row['postCnt'];
                    array_push( $users , $user);
                }

                return $users;
                

    }

}