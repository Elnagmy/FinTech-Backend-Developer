<?php
require_once('../config.php');
require_once (ROOT_DIR.'\controllers\CommentController.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
require_once (ROOT_DIR.'\helpers\Vaildators.php');
$postCont = new PostController ();

$commentController = new CommentController();
$userController = new userController();

$id = $_REQUEST['id'];


if($id == null || $userController->getUserIdFromSession() ==null || $id =="") {
   echo "Bad Request or your you have to login to access this!";
   die();
}
$comment = $commentController->getCommentbyId($id);
if ($comment=="NotFound"){
    echo "Comment doesn't exist  !";
    die(); 
}
$post =$comment->post;
$authorized = isAuthorized($comment->author->id , $post->author->id  , $userController->getUserIdFromSession());
if ($authorized){
    $comment = $commentController->DeleteCommentbyId($id);
}else {
    echo "You are not authorized to delete this comment !";
    die();
}

function isAuthorized($commentAuthorId, $postAuthorId , $loginUserId) {
    $vaildator = new Vaildator();
    return ($vaildator->checkIfUserisAdmin() || $commentAuthorId == $loginUserId || $postAuthorId == $loginUserId ) ;
}


