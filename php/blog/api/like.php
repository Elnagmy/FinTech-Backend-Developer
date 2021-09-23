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
if($type == "post") $postControler->likePost($id, $userController->getUserIdFromSession());
if($type == "comment") $commentController->likeComment($id, $userController->getUserIdFromSession());
echo true;
