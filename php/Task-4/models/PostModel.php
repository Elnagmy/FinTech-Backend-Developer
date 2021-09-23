<?php

require_once (ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');
require_once  (ROOT_DIR.'\models\Model.php');
require_once  (ROOT_DIR.'\models\CommentModel.php');  
require_once  (ROOT_DIR.'\models\UserModel.php');  


class PostModel extends Model{

    public $postCount ; 

    function getlatestPosts ($count , $like_by_user_id ){

        return $this->getPosts($count, 1, null , null , null , null, "publish_date" , "desc" , $like_by_user_id ) ;
       
    }



    function getPostCommentsCount($postId) {
    $sql = "SELECT COUNT(0) as cnt FROM comments WHERE post_id=$postId";
    $result =  parent::getRow($sql);
    if ($result == null) return 0;
    return $result['cnt'];
    }

    function getPostLikesCount($postId) {
        $sql = "SELECT COUNT(0) as cnt FROM likes WHERE post_id = $postId";
      
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


    function getPosts($page_size, $page = 1, $category_id = null, $tag_id = null, $user_id = null, $q = null ,$order_field, $order_by  , $like_by_user_id = null ) {
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
            $post->set_likesCont( $this->getPostLikesCount($post->id) );
            $post->set_tags( $this->getPostTags($post->id) );
            if ($like_by_user_id) {
                $post->set_liked_by_me ($this->getIfLikedByMe ($post->id, $like_by_user_id));
            } else {
                $post->set_liked_by_me ( false);
            }

            
            array_push($PostArray, $post);
        }
    
    
        return $PostArray;
    }

    function getIfLikedByMe($post_id, $user_id)
{
    $sql = "SELECT id FROM likes WHERE post_id=? and user_id=?";
    return parent::getRow($sql, 'ii', [$post_id, $user_id]) != null;
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



    public function getPostById($post_id , $loginUserId =null){
        $commentModel = new CommentModel();
        $userModel = new UserModel();
        $sql = "select p.* ,u.id as author_id , u.name as author_name,  u.profile_image as author_image , c.name as catogory , c.id as catogory_id FROM posts p
        INNER JOIN categories c ON c.id=p.category_id
        INNER JOIN users u ON u.id=p.user_id 
        where p.id = ".$post_id ;
        $result =  parent::getRow($sql);
        $author = new User($result['author_id'] , $result['author_name'] , null,null,null,null,null);
        $author-> postsCount = $this->get_Posts_count_per_user($result['author_id'] );
        $author-> followers_count = $userModel ->get_followers_count($result['author_id'] );
        $author-> followed_by_me = $userModel->isFollowed_by_login_user($result['author_id']  , $loginUserId);
        $author->pofileImage = $result['author_image'] ;
        $post = new Post($result['id'] , $result['title'], $result['content'] , $result['publish_date'] , $result['catogory'] , $author) ;
        $post->set_tags( $this->getPostTags($post->id) );
        $post->image = $result['image'];
        $comments = $commentModel->getComments($post->id , $loginUserId);
        $post->set_comments($comments );
        $post->set_commentsCont(($comments !=0 ? count($comments) : 0));
        $post ->category_id = $result['catogory_id'];
        return $post;
    }


    function get_Posts_count_per_user ( $user_id){
        $sql = "SELECT count(0) as cnt FROM posts 
                Where user_id=?";
        $result =  parent::getRow($sql , "i",[$user_id]);
        if ($result == null) return 0;
        return $result['cnt'];

    }


    function addWhereConditions($sql, $category_id = null, $tag_id = null, $user_id = null, $q = null, &$types, &$vals){
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


function editPost($id, $request, $image)
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
                WHERE id = ? "; 
        $post_id = parent::editData($sql, 'ssssii', [
                $request['title'],
                $request['content'],
                $image,
                $request['publish_date'],
                $request['category_id'],
                $id
            ]);
    }else { 
        $sql = "UPDATE posts 
                SET title = ? , 
                content =? , 
                publish_date = ? ,
                updated_at = CURRENT_TIMESTAMP() ,
                category_id = ? 
                WHERE id = ? "; 
        $post_id = parent::editData($sql, 'sssii', [
            $request['title'],
            $request['content'],
            $request['publish_date'],
            $request['category_id'],
            $id
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



function deletePost($id)
{
    $sql = "DELETE FROM posts WHERE id=?";
    return parent::deleteData($sql,
        'i',[$id] );
}


function likePost($id, $user_id)
{
    $sql = "INSERT INTO likes (id,post_id,user_id) VALUES (null,?,?)";
    return parent::deleteData($sql, 'ii', [$id, $user_id]);
}
function unlikePost($id, $user_id)
{
    $sql = "DELETE FROM likes WHERE post_id=? AND user_id=?";
    return parent::deleteData($sql, 'ii', [$id, $user_id]);
}


function addComent($post_id , $userid , $comment) {
    $sql = "INSERT INTO comments (id, comment, comment_date, post_id, user_id) VALUES (NULL, ?, current_timestamp(), ?, ?)";
    return parent::addData($sql, 'sii', [$comment, $post_id,$userid]);
}


    

}