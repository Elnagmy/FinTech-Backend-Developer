<?php
require_once('../config.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\CommentController.php');
$userController = new UserController();
$postControler = new PostController();
$commentController = new CommentController();
$id = $_REQUEST['id'];
$type=$_REQUEST['type'];
if($type == "post") $postControler->unlikePost($id, $userController->getUserIdFromSession());
if($type == "comment") $commentController->unLikeComment($id, $userController->getUserIdFromSession());
echo true;

