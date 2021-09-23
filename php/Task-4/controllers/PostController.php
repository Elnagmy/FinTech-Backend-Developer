<?php
require_once(ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\models\PostModel.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
class PostController {
    public $postCount ;
   
    function getLatestPosts($postCont) {
        $UserController = new UserController();
        $model = new PostModel();
     return $model->getlatestPosts($postCont ,  $UserController->getUserIdFromSession() );
    }


    function getPosts($page_size, $page, $category_id, $tag_id, $user_id, $q ,$order_field = null , $order_by=null) {
        $model = new PostModel();
        $UserController = new UserController();
        if ( $order_field ==null ) $order_field = "publish_date" ;
        if ( $order_by ==null ) $order_by = "desc";
        $result = $model->getPosts($page_size, $page, $category_id , $tag_id , $user_id , $q ,$order_field, $order_by , $UserController->getUserIdFromSession() );
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
     $UserController = new UserController();
     return $model->getlatestPosts($postcount , $UserController->getUserIdFromSession());
    }


    public function getPostById($post_id)
    {
        $model = new PostModel();
        $UserController = new UserController();
        return $model->getPostById($post_id, $UserController->getUserIdFromSession());
    }


    function getMyPosts($page_size, $page, $user_id, $q, $order_field, $order_by)
{
    return [
        'data' => $this->getPosts($page_size, $page, null, null, $user_id, $q, $order_field, $order_by),
        'count' => $this->get_PostsCount()
    ];
}

function getAllPosts($page_size, $page, $user_id, $q, $order_field, $order_by)
{
    return [
        'data' => $this->getPosts($page_size, $page, null, null, null, $q, $order_field, $order_by),
        'count' => $this->get_PostsCount()
    ];
}


function getUploadedImage($files){
    if ($files['image']['size'] == 0 ) return null;
    move_uploaded_file($files['image']['tmp_name'],ROOT_DIR.'/images/'.$files['image']['name']);
    return '/images/' . $files['image']['name'];
}

function addNewPost($request, $user_id, $image){
    $model = new PostModel();
    $request['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $request['content'] = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    return $model->addNewPost($request, $user_id, $image);
}


function editPost($post_id, $request, $image){
    $request['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $request['content'] = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $model = new PostModel();
    return $model->editPost($post_id, $request , $image);
}


function deletePost($id)
{
    $model = new PostModel();
    return $model->deletePost($id);
    
}

function likePost($id, $user_id)
{
    $model = new PostModel();
    return $model->likePost($id, $user_id);
}

function unlikePost($id, $user_id)
{
    $model = new PostModel();
    return $model->unlikePost($id, $user_id);
}


function addComent($post_id , $userid , $comment) {
    $model = new PostModel();
    return $model->addComent($post_id , $userid , $comment);
}


    
}