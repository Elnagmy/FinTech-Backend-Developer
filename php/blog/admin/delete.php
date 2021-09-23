<?php

require_once ('..\config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
require_once (ROOT_DIR.'\helpers\Vaildators.php');
if (!isset($_REQUEST['id'])) {
    header('Location:index.php');
    die();
}

$UserController = new UserController ();
$PostController = new PostController ();
$Vaodator = new Vaildator();
$post_id = $_REQUEST['id'];
$post = $PostController->getPostById($post_id);
if (!$Vaodator->checkIfUserCanEditPost($post)) {
    header('Location:index.php');
    die();
}
$PostController->deletePost($post_id);
header('Location:index.php');
die();
