<?php
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
require_once  (ROOT_DIR.'\models\Model.php');

class PostModel extends Model{

    public $postCount ; 

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



    function getPostCommentsCount($postId) {
    $sql = "SELECT COUNT(0) as cnt FROM comments WHERE post_id=$postId";
    $result =  parent::getRow($sql);
    if ($result == null) return 0;
    return $result['cnt'];
    }

    function getPostTags($postId) {
        $sql = "SELECT t.id as TAG_ID, t.name as TAG_NAME FROM post_tags pt
        INNER JOIN tags t ON t.id=pt.tag_id
        WHERE pt.post_id=$postId";
        return  parent::getRows($sql);
    }


    function getPosts($page_size, $page = 1, $category_id = null, $tag_id = null, $user_id = null, $q = null) {
        
        $sql = "SELECT p.*, c.name as category ,  u.id as  author_ID , u.name AS author_Name FROM posts p
        INNER JOIN categories c ON c.id=p.category_id
        INNER JOIN users u ON u.id=p.user_id ";

        $queryPram = " WHERE 1=1 ";
        if ($category_id != null) {
            $queryPram .= " AND category_id=$category_id";
        }
        if ($user_id != null) {
            $queryPram .= " AND user_id=$user_id";
        }
        if ($tag_id != null) {
            $queryPram .= " AND p.id IN (SELECT post_id FROM post_tags WHERE tag_id=$tag_id)";
        }
        if ($q != null) {
            $queryPram .= " AND (title like '%$q%' OR content like '%$q%')";
        }
        $this->set_PostsCount($queryPram) ;

        $offset = ($page - 1) * $page_size;
        $sql = $sql . $queryPram . " ORDER BY publish_date desc limit $offset,$page_size";
        $result =  parent::getRows($sql);
        $PostArray =[] ; 
    
        foreach ($result as $row){
            $author = new User($row['author_ID'] , $row['author_Name'],null,null,null,null);
            $post = new Post($row['id'] ,$row['title'], $row['content'] , $row['publish_date'], $row['category'], $author) ;
            $post->image =$row['image'];
            
            $post->set_commentsCont( $this->getPostCommentsCount($post->id) );
            $post->set_tags( $this->getPostTags($post->id) );
            array_push($PostArray, $post);
        }
    
    
        return $PostArray;
    }

    function set_PostsCount($queryPram) { 
        $sql = "select count(0) as cnt FROM posts p
        INNER JOIN categories c ON c.id=p.category_id
        INNER JOIN users u ON u.id=p.user_id ".$queryPram ;
        $result =  parent::getRow($sql);
        $this->postCount = $result ['cnt'];
    }

    function get_PostsCount() {
        return $this->postCount ;
    }
    

}