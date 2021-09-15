<?php
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
require_once  (ROOT_DIR.'\models\Model.php');

class PostModel extends Model{

    function getlatestPosts ($count){
     

        $result =[] ; 
        $SQL = 'SELECT p.* , u.id as author_ID, u.name as author_Name, c.name as category FROM posts as p 
                INNER join users u on u.id = p.user_id
                INNER JOIN categories as c on c.id = p.category_id 
                order by p.publish_date desc LIMIT '.$count;
            
        $returnedResul =  parent::getRows($SQL);
        foreach ($returnedResul as $row){
            
            $author = new User($row['author_ID'] , $row['author_Name'],null,null,null,null);
            $post = new Post($row['id'] ,$row['title'], $row['content'] , $row['publish_date'], $row['category'], $author) ;
            $post->image =$row['image'];
            $post->set_commentsCont( $this->getPostCommentsCount($post->id) );
            $post->set_tags( $this->getPostTags($post->id) );
            array_push($result, $post);
        }
        return  $result ;     
    }



    function getPostCommentsCount($postId)
{
    $sql = "SELECT COUNT(0) as cnt FROM comments WHERE post_id=$postId";
    $result =  parent::getRow($sql);
    if ($result == null) return 0;
    return $result['cnt'];
}

function getPostTags($postId)
{
    $sql = "SELECT t.id,t.name FROM post_tags pt
    INNER JOIN tags t ON t.id=pt.tag_id
    WHERE post_id=$postId";
    return  parent::getRows($sql);
}
    

}