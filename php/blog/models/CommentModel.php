<?php

require_once (ROOT_DIR.'\beens\Comment.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\models\Model.php');



class CommentModel extends Model{ 
    function getComments($postId , $loginUserId= null) {
        $sql = "SELECT c.* , u.id as author_id , u.name as author_name , u.profile_image as profile_image
                FROM comments c 
                inner join users u on c.user_id = u.id  
                WHERE post_id=$postId";
              
        $result =  parent::getRows($sql);
        $comments = [];
        if ($result == null) return 0;
        
        foreach( $result as $row ){
            $author = new User($row['author_id'], $row['author_name'], null,null,null,null);
            $author->pofileImage = $row['profile_image'];
            $comment = new Comment($row['id'] , $row['comment'] , $row['comment_date'], $author);
            $comment -> likes_count = $this->getCommentsikesCount($row['id'] );
            $comment -> liked_by_me = $this->isComment_liked_by_me($row['id'] , $loginUserId );
     
            array_push($comments , $comment);
        }
        return $comments;
        }


    function likeComment($id, $userId) {

        $sql = "INSERT INTO comment_likes (id, comment_id, user_id, like_date) VALUES (NULL, ?, ? , current_timestamp())" ;
        $result =  parent::addData($sql,"si",[$id, $userId]);
    }

    function unLikeComment($id, $userId) {

        $sql = "DELETE FROM comment_likes Where comment_id= ? and User_id = ? " ;
        $result =  parent::deleteData($sql,"ii",[$id, $userId]);
    }


    function getCommentsikesCount($commetId) {
        $sql = "SELECT COUNT(0) as cnt FROM comment_likes WHERE comment_id = $commetId ";
      
        $result =  parent::getRow($sql);
        if ($result == null) return 0;
        return $result['cnt'];
        }


    function  isComment_liked_by_me ( $commentID , $loginUserId ){
        $sql ="SELECT COUNT(0) as cnt from  comment_likes where 
                comment_id  = ? and  user_id=?";
        $result =  parent::getRow($sql , "ii",[$commentID , $loginUserId]);
        if ($result == null || $result['cnt'] == 0) return false;
        return true;
    }


    function getCommentbyId($id) {
    $sql = "SELECT C.* , U.id as AuthorID , U.name as Author_name, P.USER_ID as PostAuthor  from Comments C 
            INNER Join USERS U on U.id = C.User_ID
            INNER Join POSTS P on P.id = C.POST_ID
            WHERE C.id = ? ";
    $result= parent::getRow($sql , "i",[$id]);
    if ( $result == null) return "NotFound";
    $author = new User($result["AuthorID"], $result["Author_name"],null,null,null,null);
    $Postauthor = new User($result["PostAuthor"], null,null,null,null,null);
    $post = new Post($result["post_id"], null ,null, null , null,  $Postauthor);
    $comment = new Comment($result["id"],$result["comment"] , $result["comment_date"], $author) ;
    $comment ->post = $post;
    return $comment;
    }

    function DeleteCommentbyId($id) {
        $sql = "DELETE FROM COMMENTS 
                where id= ?" ;
        return  parent::deleteData($sql , "i",[$id]);
    }

    
}