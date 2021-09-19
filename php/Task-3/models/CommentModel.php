<?php
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Comment.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\models\Model.php');

class CommentModel extends Model{ 
    function getComments($postId) {
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
            array_push($comments , $comment);
        }
        return $comments;
        }
}