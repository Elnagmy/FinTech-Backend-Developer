<?php
require_once ('..\config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\helpers\Vaildators.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
define('MAX_PAGE_SIZE', 3);
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$page_size = MAX_PAGE_SIZE;
$order_field = isset($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'id';
$order_by = isset($_REQUEST['order_by']) ? $_REQUEST['order_by'] : 'asc';
$q = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';
$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
$action= isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
function getUrl($page, $q, $order_field, $order_by)
{
 
    return "users.php?page=$page&q=$q&order_field=$order_field&order_by=$order_by";
}
function getSortingUrl($field, $oldOrderField, $oldOrderBy, $q)
{
   
    if ($field == $oldOrderField && $oldOrderBy == 'asc') {
        return "users.php?page=1&q=$q&order_field=$field&order_by=desc";
    }
    if ($field == $oldOrderField && $oldOrderBy == 'desc') {
        return "users.php?page=1&q=$q";
    }
    return  "users.php?page=1&q=$q&order_field=$field&order_by=asc";
}

function getSortFlag($field, $oldOrderField, $oldOrderBy)
{
    if ($field == $oldOrderField && $oldOrderBy == 'asc') {
        return "<i class='fa fa-sort-up'></i>";
    }
    if ($field == $oldOrderField && $oldOrderBy == 'desc') {
        return "<i class='fa fa-sort-down'></i>";
    }
    return  "";
}
function getUserId()
{
    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if (isset($_SESSION['user'])) return $_SESSION['user']->id;
    return 0;
}

$userController = new UserController ();
$vaildator = new Vaildator ();
$authorised_user = $vaildator->checkIfUserisAdmin() ;
if (!$authorised_user) {
        header('Location:'.BASE_URL);
        die();
}

if ($action) {
    if($action == "Block") $userController->blockUser($user_id);
    if($action == "UnBlock") $userController->unBlockUser($user_id);
    if($action == "Promote to Admin") $userController->PromoteTOAdmin($user_id);
    if($action == "Revoke Admin") $userController->RevokeAdmin($user_id);
    if($action == "remove") $userController->removeUser($user_id);
}
$users = $userController->getallUsers($page_size, $page,  $q, $order_field, $order_by );
$page_count = ceil($users['count'] / $page_size);
/*
$posts = ['data'=>[],'count'=>100,'order_field'=>'title','order_by'=>'asc']
*/
require_once ROOT_DIR.'\layouts\header.php';
?>
<!-- Page Content -->
<!-- Banner Starts Here -->
<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>All users</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Banner Ends Here -->
<section class="blog-posts">
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="all-blog-posts">
                    <div class="row">
                        <div class="col-md-2"><h5>search for Post : </h5></div>
                        <div class="col-md-10">
                            <div class="sidebar-item search">
                                <form id="search_form" name="gs" method="GET" action="">
                                    <input type="text" class="form-control" value="<?= isset($_REQUEST['q']) ? $_REQUEST['q'] : '' ?>" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
                                </form>
                            </div>
                        </div>
                        <br>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="<?= getSortingUrl('title', $order_field, $order_by, $q) ?>">Name <?= getSortFlag('Name', $order_field, $order_by) ?></a></th>
                                    <th><a href="<?= getSortingUrl('title', $order_field, $order_by, $q) ?>">User Name <?= getSortFlag('userName', $order_field, $order_by) ?></a></th>
                                    <th><a href="<?= getSortingUrl('c.name', $order_field, $order_by, $q) ?>">email <?= getSortFlag('email', $order_field, $order_by) ?></a></th>
                                    <th>User Type</th>
                                    <th>Profile Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($users['data'] as $user) {
                                    $userType = ($user->type ==1 ) ? "Administrator" :"Basic Access";
                                    $userStatus = ($user->Status ==1 ) ? "Block" :"UnBlock";
                                    $userAccess = ($user->type ==1 ) ? "Revoke Admin" :"Promote to Admin";
                                    $img_src = BASE_URL . $user->pofileImage;
                                    echo "<tr id='USER_$user->id' >
                                    <td>$i</td>
                                    <td>$user->name</td>
                                    <td>{$user->userName}</td>
                                    <td>$user->email</td>
                                    <td>{$userType}</td>
                                    <td><img src='{$img_src}' width='200' height='200'/></td>
                                    <td>
                                    <a href='users.php?user_id={$user->id}&action={$userStatus}' class='btn btn-primary'>$userStatus</a> <br>
                                    <a href='users.php?user_id={$user->id}&action={$userAccess}' class='btn btn-primary'>$userAccess</a> <br>
                                    <a onclick='return confirm(\"Are you sure ?\")' href='users.php?user_id={$user->id}&action=remove' class='btn btn-danger'>remove</a>
                                    
                                    </td>
                                    </tr>";
                                    $i++;
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12">
                        <ul class="page-numbers">
                            <?php
                            $prevUrl = getUrl($page - 1, $q, $order_field, $order_by);
                            $nxtUrl = getUrl($page + 1, $q, $order_field, $order_by);

                            if ($page > 1) echo "<li><a href='{$prevUrl}'><i class='fa fa-angle-double-left'></i></a></li>";

                            for ($i = 1; $i <= $page_count; $i++) {
                                $url = getUrl($i, $q, $order_field, $order_by);
                                echo "<li class=" . ($i == $page ? "active" : "") . "><a href='{$url}'>{$i}</a></li>";
                            }

                            if ($page < $page_count) echo "<li><a href='{$nxtUrl}'><i class='fa fa-angle-double-right'></i></a></li>";
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
require_once ROOT_DIR.'\layouts\footer.php';
    ?>