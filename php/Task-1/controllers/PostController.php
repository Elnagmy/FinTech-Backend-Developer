<?php
require_once ('config.php');
require_once(ROOT_DIR.'\beens\Posts.php');
require_once (ROOT_DIR.'\models\PostModel.php');
class PostController {

    function getLatestPosts($postCont) {
     $model = new PostModel();
     return $model->getlatestPosts($postCont);
    }
    
}