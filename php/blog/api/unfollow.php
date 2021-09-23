<?php
require_once('../config.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
$userController = new UserController();
$id = $_REQUEST['id'];
if($id && $userController->getUserIdFromSession())  $userController->unFollow($id, $userController->getUserIdFromSession());
echo true;
