<?php
require_once('./config.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
$usercontroler = new UserController();
$usercontroler->logOut();
