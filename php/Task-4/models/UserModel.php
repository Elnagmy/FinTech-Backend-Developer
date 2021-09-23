<?php

require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
require_once  (ROOT_DIR.'\models\Model.php');
class UserModel extends Model{
    public $usersCount ; 

    function set_usersCount ($queryPram , $types, $vals){

        $sql = "select count(0) as cnt FROM Users
                ".$queryPram ;

        $result =  parent::getRow($sql , $types, $vals);
        $this->usersCount = $result ['cnt'];
    }


    function get_usersCount (){
        return $this->usersCount ;
    }



    function insertNewUser( $user){
 
        $sql = "INSERT INTO users (id,name,username,password,email,phone , profile_image) values (null, ?, ? , md5(?) ,? ,? , ?  )";
        $vals = [$user->name , $user->userName ,$user->password, $user->email , $user->phone , $user->pofileImage] ;
   
        $result = parent::addData($sql, "ssssss", $vals );
        return $result;
    }


    public function TryUserLogin ( $user_nameOrEmail , $password) {
        $values = [$user_nameOrEmail,$password];
        $types = 'ss';
        $sql = "SELECT * FROM users WHERE username=? and password = md5(?) limit 1;";
        $result = parent::getRow($sql , $types , $values); 
        if (  $result !=null){
            $loginUser = new User ($result["id"] , $result["name"] ,$result["username"], $result["password"],$result["email"],$result["phone"]);
            $loginUser->type = $result["type"];
            $loginUser->Status = $result["Status"];
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


   function getallUsers ($page_size, $page,  $q, $order_field, $order_by ) {
        $sql = "SELECT * 
                FROM users ";
        $queryPram = " WHERE 1=1 ";
        $types = null;
        $vals = null;

        if ($q){
            $queryPram .= " And ( email like ? OR name like ? OR userName like ? )" ;
            $types = 'sss';
            $vals = ['%' . $q . '%','%' . $q . '%','%' . $q . '%'];
        }
        $sql=   $sql . $queryPram;
  
        $this->set_usersCount($queryPram , $types, $vals);
        $offset = ($page - 1) * $page_size;
        $sql = $sql . " ORDER BY $order_field $order_by limit $offset,$page_size";
        $result =  parent::getRows($sql, $types, $vals);
        $UsersArray =[] ; 
       

        foreach ($result as $row){
            $user = new User($row['id'] , $row['name'],$row['username'],null,$row['email'],$row['phone']);
            $user->type = $row['type'];
            $user->Status = $row['Status'];
            $user->pofileImage = $row['profile_image'];
            array_push($UsersArray, $user);
        }


        return $UsersArray;

   }


   function blockUser ( $user_id){
        $sql = "update users 
                Set Status = 0 
                where id = ?";
        return parent::editData($sql , "i",[$user_id]);
    }

    function unBlockUser ( $user_id){
        $sql = "update users 
        Set Status = 1 
        where id = ?";
        return parent::editData($sql , "i",[$user_id]);
        
    }

    function PromoteTOAdmin ( $user_id){
        $sql = "update users 
        Set Type = 1 
        where id = ?";
        return parent::editData($sql , "i",[$user_id]);
    
    }

    function RevokeAdmin ( $user_id){
        $sql = "update users 
        Set Type = 0 
        where id = ?";
        return parent::editData($sql , "i",[$user_id]);
    }

    function removeUser ( $user_id){
        $sql = "Delete from 
                users 
                where id = ?";
        return parent::deleteData($sql , "i",[$user_id]);
    }


    function get_followers_count($user_id ){
        $sql = "SELECT Count(0)  as cnt FROM follows where following_id = ? ";
        $result = parent::getRow($sql , "i", [$user_id]);
        if ($result == null) return 0;
        return $result['cnt'];
    }


    function isFollowed_by_login_user($userId  , $loginUserId){
        $sql = "SELECT id FROM follows WHERE follower_id =? and following_id =?";
        return parent::getRow($sql, 'ii', [$loginUserId, $userId]) != null;
    }


    
    function follow ( $following_id , $follower_id) {
        $sql = "INSERT INTO follows (id, follower_id, following_id, follow_date) 
                VALUES (NULL, ?, ?, current_timestamp())";
        return parent::addData($sql, 'ii', [ $follower_id , $following_id]);
        
    }


    function unFollow ( $following_id , $follower_id) {
        $sql = "DELETE FROM follows 
              WHERE follower_id= ? and  following_id = ? " ;
        return parent::deleteData($sql , "ii",  [ $follower_id , $following_id] );
        
    }



}