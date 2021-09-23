<?php
require_once ('..\config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\helpers\Vaildators.php');
require_once (ROOT_DIR.'\controllers\TagController.php');
require_once (ROOT_DIR.'\controllers\CategoryController.php');
function getUserId()
{
    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if (isset($_SESSION['user'])) return $_SESSION['user']->id;
    return 0;
}
$tagController = new TagController();
$categoryController = new CategoryController ();
$PostController = new PostController ();
$vaildator = new Vaildator();
$tags = $tagController->getAllTags();
$categories = $categoryController->getAllCategories();
if (isset($_REQUEST['title'])) {
    $errors = $vaildator->validatePostCreate($_REQUEST);
    if (count($errors) == 0) {

        if ($PostController->addNewPost($_REQUEST, getUserId(), $PostController->getUploadedImage($_FILES))) {
            header('Location:posts.php');
            die();
        } else {
            $generic_error = "Error while adding the post";
        }
    }
}

require_once(ROOT_DIR . '\layouts\header.php');
?>
<!-- Page Content -->
<!-- Banner Starts Here -->
<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Add Post</h4>
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
                        <div class="col-sm-12">
                            <form method="Post" enctype="multipart/form-data">
                                <input name="title" placeholder="title" class="form-control" />
                                <?= isset($errors['title']) ? "<span class='text-danger'>" . $errors['title'] . "</span>" : "" ?>
                                <textarea name="content" placeholder="content" class="form-control"></textarea>
                                <?= isset($errors['content']) ? "<span class='text-danger'>" . $errors['content'] . "</span>" : "" ?>
                                <label>Upload Image<input type="file" name="image" /></label><br />
                                <?= isset($errors['image']) ? "<span class='text-danger'>" . $errors['image'] . "</span>" : "" ?>
                                <label>Publish date<input type="date" name="publish_date" class="form-control"></label>
                                <?= isset($errors['publish_date']) ? "<span class='text-danger'>" . $errors['publish_date'] . "</span>" : "" ?>
                                <select name="category_id" class="form-control">
                                    <option value="">Select category</option>
                                    <?php
                                    foreach ($categories as $category) {
                                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <?= isset($errors['category_id']) ? "<span class='text-danger'>" . $errors['category_id'] . "</span>" : "" ?>
                                <select name="tags[]" multiple class="form-control">
                                    <?php
                                    foreach ($tags as $tag) {
                                        echo "<option value='{$tag['id']}'>{$tag['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <?= isset($errors['tags']) ? "<span class='text-danger'>" . $errors['tags'] . "</span>" : "" ?>
                                <button class="btn btn-success">Create Post</button>
                                <a href="mypost.php" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once(ROOT_DIR . '\layouts\footer.php') ?>