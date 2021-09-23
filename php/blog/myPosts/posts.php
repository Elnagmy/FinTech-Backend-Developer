<?php
require_once ('..\config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
define('MAX_PAGE_SIZE', 3);
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$page_size = MAX_PAGE_SIZE;
$order_field = isset($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'id';
$order_by = isset($_REQUEST['order_by']) ? $_REQUEST['order_by'] : 'asc';
$q = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';
function getUrl($page, $q, $order_field, $order_by)
{
 
    return "posts.php?page=$page&q=$q&order_field=$order_field&order_by=$order_by";
}
function getSortingUrl($field, $oldOrderField, $oldOrderBy, $q)
{
   
    if ($field == $oldOrderField && $oldOrderBy == 'asc') {
        return "posts.php?page=1&q=$q&order_field=$field&order_by=desc";
    }
    if ($field == $oldOrderField && $oldOrderBy == 'desc') {
        return "posts.php?page=1&q=$q";
    }
    return  "posts.php?page=1&q=$q&order_field=$field&order_by=asc";
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
$postControler = new PostController ();
$userController = new UserController ();
$posts = $postControler->getMyPosts($page_size, $page, getUserId(), $q, $order_field, $order_by , $userController->getUserIdFromSession());
$page_count = ceil($posts['count'] / $page_size);
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
                        <h4>My Posts</h4>
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
                        <div class="col-md-2"><a href="addPost.php" class="btn btn-success">Add Post</a></div>
                        <div class="col-md-10">
                            <div class="sidebar-item search">
                                <form id="search_form" name="gs" method="GET" action="">
                                    <input type="text" class="form-control" value="<?= isset($_REQUEST['q']) ? $_REQUEST['q'] : '' ?>" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
                                </form>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="<?= getSortingUrl('title', $order_field, $order_by, $q) ?>">Title <?= getSortFlag('title', $order_field, $order_by) ?></a></th>
                                    <th><a href="<?= getSortingUrl('c.name', $order_field, $order_by, $q) ?>">Category <?= getSortFlag('c.name', $order_field, $order_by) ?></a></th>
                                    <th>Tags</th>
                                    <th>Image</th>
                                    <th><a href="<?= getSortingUrl('publish_date', $order_field, $order_by, $q) ?>">Publish Date <?= getSortFlag('publish_date', $order_field, $order_by) ?></a></th>
                                    <th>#Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($posts['data'] as $post) {
                                  
                                    $tags = '';
                                    foreach ($post->tags as $tag) {
                                        $tags .= "<span class='tag'>{$tag['TAG_NAME']}</tag> <br>";
                                    }
                                    $img_src = BASE_URL . $post->image;
                                    echo "<tr>
                                    <td>$i</td>
                                    <td>$post->title</td>
                                    <td>$post->category</td>
                                    <td>{$tags}</td>
                                    <td><img src='{$img_src}' width='200' height='200'/></td>
                                    <td>{$post->publish_date}</td>
                                    <td>{$post->commentsCont}</td>
                                    <td>
                                    <a href='edit.php?post_id={$post->id}' class='btn btn-primary'>Edit</a> <br>
                                    <a href='".BASE_URL."post-details.php?post_id={$post->id}' class='btn btn-primary'>Manage Comments</a> <br>
                                    <a onclick='return confirm(\"Are you sure ?\")' href='delete.php?id={$post->id}' class='btn btn-danger'>Delete</a> 
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