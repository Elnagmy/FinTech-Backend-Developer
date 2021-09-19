<?php
require_once ('config.php');
require_once(ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\models\PostModel.php');
class PostController {
    public $postCount ;

    function getLatestPosts($postCont) {
     $model = new PostModel();
     return $model->getlatestPosts($postCont);
    }


    function getPosts($page_size, $page, $category_id, $tag_id, $user_id, $q ,$order_field = null , $order_by=null) {
        $model = new PostModel();
        if ( $order_field ==null ) $order_field = "publish_date" ;
        if ( $order_by ==null ) $order_by = "desc";
        $result = $model->getPosts($page_size, $page, $category_id , $tag_id , $user_id , $q ,$order_field, $order_by );
        $this->set_PostsCount($model->get_PostsCount());
        return $result;
    }

    function getNumberOfPages($postCount, $page_size ) { 
        $postCount-=1;
        return (int) ($postCount/$page_size) +1 ;
    }


    function set_PostsCount($postscount) { 
        $this->postCount = $postscount;
    }

    function get_PostsCount() {
        return $this->postCount ;
    }

    function getLatestPostsPerCategory($postcount) {
     $model = new PostModel();
     return $model->getlatestPosts($postcount);
    }


    public function getPostById($post_id)
    {
        $model = new PostModel();
        return $model->getPostById($post_id);
    }


    function getMyPosts($page_size, $page, $user_id, $q, $order_field, $order_by)
{
    return [
        'data' => $this->getPosts($page_size, $page, null, null, $user_id, $q, $order_field, $order_by),
        'count' => $this->get_PostsCount()
    ];
}


function getUploadedImage($files){
    move_uploaded_file($files['image']['tmp_name'],ROOT_DIR.'/images/'.$files['image']['name']);
    return '\images\\' . $files['image']['name'];
}

function addNewPost($request, $user_id, $image){
    $model = new PostModel();
    return $model->addNewPost($request, $user_id, $image);
}


function editPost($post_id, $request, $user_id, $image){
 
    $model = new PostModel();
    return $model->editPost($post_id, $request, $user_id, $image);
}


    
}