<?php
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
require_once  (ROOT_DIR.'\models\Model.php');
require_once  (ROOT_DIR.'\models\CommentModel.php');

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


    function getPosts($page_size, $page = 1, $category_id = null, $tag_id = null, $user_id = null, $q = null ,$order_field, $order_by   ) {
        
        $sql = "SELECT p.*, c.name as category ,  u.id as  author_ID , u.name AS author_Name FROM posts p
        INNER JOIN categories c ON c.id=p.category_id
        INNER JOIN users u ON u.id=p.user_id 
        WHERE 1=1";

        $queryPram = "";
        $types = '';
        $vals = [];
        $queryPram = $this->addWhereConditions($queryPram , $category_id, $tag_id, $user_id, $q, $types, $vals);
        $this->set_PostsCount($queryPram , $types, $vals);
        $offset = ($page - 1) * $page_size;
        $sql = $sql . $queryPram . " ORDER BY $order_field $order_by limit $offset,$page_size";
        $result =  parent::getRows($sql, $types, $vals);
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

    function set_PostsCount($queryPram, $types, $vals) { 
        $sql = "select count(0) as cnt FROM posts p
        INNER JOIN categories c ON c.id=p.category_id
        INNER JOIN users u ON u.id=p.user_id ".$queryPram ;
        $result =  parent::getRow($sql , $types, $vals);
        $this->postCount = $result ['cnt'];
    }

    function get_PostsCount() {
        return $this->postCount ;
    }

    function getLatestPostsPerCategory($postcount) {
        $result =[] ; 
        $SQL = "select u.id as author_ID, u.name as author_Name , p.id as POST_ID, p.title as POST_TITLE ,
                c.name as category , p.publish_date as POST_DATE , p.image as POST_image
                FROM posts p 
                INNER join categories c on c.id = p.category_id 
                INNER join users u on u.id = p.user_id 
                group by c.name 
                ORDER by p.publish_date LIMIT  ".$postcount;
            
        $returnedResul =  parent::getRows($SQL);
        foreach ($returnedResul as $row){
            
            $author = new User($row['author_ID'] , $row['author_Name'],null,null,null,null);
            $post = new Post($row['POST_ID'] ,$row['POST_TITLE'], null , $row['POST_DATE'], $row['category'], $author) ;
            $post->image =$row['POST_image'];
            $post->set_commentsCont( $this->getPostCommentsCount($post->id) );
            $post->set_tags( $this->getPostTags($post->id) );
            array_push($result, $post);
        }
        return  $result ;  
       }



       public function getPostById($post_id)
       {
        $commentModel = new CommentModel();
        $sql = "select p.* ,u.id as author_id , u.name as author_name, c.name as catogory FROM posts p
        INNER JOIN categories c ON c.id=p.category_id
        INNER JOIN users u ON u.id=p.user_id 
        where p.id = ".$post_id ;

        $result =  parent::getRow($sql);
        $author = new User($result['author_id'] , $result['author_name'] , null,null,null,null,null);
        $post = new Post($result['id'] , $result['title'], $result['content'] , $result['publish_date'] , $result['catogory'] , $author) ;
        $post->set_tags( $this->getPostTags($post->id) );
        $comments = $commentModel->getComments($post->id);

        $post->set_comments($comments );

        $post->set_commentsCont(($comments !=0 ? count($comments) : 0));
        return $post;
       }


       function addWhereConditions($sql, $category_id = null, $tag_id = null, $user_id = null, $q = null, &$types, &$vals)
{
    if ($category_id != null) {
        $types .= 'i';
        array_push($vals, $category_id);
        $sql .= " AND category_id=?";
    }
    if ($user_id != null) {
        $types .= 'i';
        array_push($vals, $user_id);
        $sql .= " AND user_id=?";
    }
    if ($tag_id != null) {
        $types .= 'i';
        array_push($vals, $tag_id);
        $sql .= " AND p.id IN (SELECT post_id FROM post_tags WHERE tag_id=?)";
    }
    if ($q != null) {
        $types .= 'ss';
        array_push($vals, '%' . $q . '%');
        array_push($vals, '%' . $q . '%');
        $sql .= " AND (title like ? OR content like ?)";
    }
    return $sql;
}


function addNewPost($request, $user_id, $image)
{
    $sql = "INSERT INTO posts(id,title,content,image,publish_date,category_id,user_id)
    VALUES (null,?,?,?,?,?,?)";
  
    $post_id = parent::addData($sql, 'ssssii', [
        $request['title'],
        $request['content'],
        $image,
        $request['publish_date'],
        $request['category_id'],
        $user_id
    ]);
 
    if ($post_id) {
        if (isset($request['tags'])) {
            foreach ($request['tags'] as $tag_id) {
                parent::addData(
                    "INSERT INTO post_tags (post_id,tag_id) VALUES (?,?)",
                    'ii',[$post_id,$tag_id]
                );
            }
        }
        return true;
    }
    return false;
}


function editPost($id, $request, $user_id, $image)
{
   
    
    $sql ="";
    $post_id = null;
    if ($image) {
        $sql = "UPDATE posts 
                SET title = ? , 
                content =? , 
                image = ?  ,
                publish_date = ? ,
                updated_at = CURRENT_TIMESTAMP() , 
                category_id = ?  
                WHERE id = ? and user_id = ?"; 
        $post_id = parent::editData($sql, 'ssssiii', [
                $request['title'],
                $request['content'],
                $image,
                $request['publish_date'],
                $request['category_id'],
                $id,
                $user_id
            ]);
    }else { 
        $sql = "UPDATE posts 
                SET title = ? , 
                content =? , 
                publish_date = ?
                updated_at = CURRENT_TIMESTAMP() ,
                category_id = ? 
                WHERE id = ? and user_id = ?"; 
        $post_id = parent::editData($sql, 'sssiii', [
            $request['title'],
            $request['content'],
            $request['publish_date'],
            $request['category_id'],
            $id,
            $user_id
        ]);
    }
    if ($post_id) {
        if (isset($request['tags'])) {
            parent::deleteData(
                "DELETE FROM post_tags 
                WHERE post_tags.post_id = ?",
                'i',[$id]
            );
    
            foreach ($request['tags'] as $tag_id) {

                parent::addData(
                    "INSERT INTO post_tags (post_id,tag_id) VALUES (?,?)",
                    'ii',[$id,$tag_id]
                );
            }
        }
        return true;
    }
    return false;
}

    

}