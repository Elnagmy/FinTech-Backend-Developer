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


    function getPosts($page_size, $page, $category_id, $tag_id, $user_id, $q) {
        $model = new PostModel();
        $result = $model->getPosts($page_size, $page, $category_id , $tag_id , $user_id , $q );
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
    
}