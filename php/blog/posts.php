<?php 
require_once ('config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\CategoryController.php');
require_once (ROOT_DIR.'\controllers\TagController.php');
define('NUMBER_OF_CATAGORIES_MAIN_PAGE' , 6);
define('NUMBER_OF_TAGS_MAIN_PAGE' , 20);
define('MAX_PAGINATION_PAGES',3);
define('PAGE_SIZE' , 2);
$category_id = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : null;
$tag_id = isset($_REQUEST['tag_id']) ? $_REQUEST['tag_id'] : null;
$q = isset($_REQUEST['posts_search']) ? $_REQUEST['posts_search'] : null;
$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
$catgory_name = isset($_REQUEST['catgory_name']) ? $_REQUEST['catgory_name'] : null;
$tag_name = isset($_REQUEST['tag_name']) ? $_REQUEST['tag_name'] : null;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;

$requestQuery = (($category_id !=null )? "category_id=".$category_id ."&":"")
                .(($tag_id !=null )? "tag_id=".$tag_id ."&" :"")
                .(($user_id !=null )? "user_id=".$user_id ."&" :"")
                .(($q !=null )? "posts_search=".$q."&":"");

$page_size = PAGE_SIZE;
$postControler = new PostController ();
$posts = $postControler->getPosts($page_size, $page, $category_id, $tag_id, $user_id , $q);
$postsCount =  $postControler->get_PostsCount();
$numberOfpages = $postControler->getNumberOfPages($postsCount , $page_size );
$catCont = new CategoryController ();
$catgories = $catCont->getcategories(NUMBER_OF_CATAGORIES_MAIN_PAGE , $catgory_name);
$tagCont = new TagController ();
$tags = $tagCont->getTags(NUMBER_OF_TAGS_MAIN_PAGE ,$tag_name);

require_once 'layouts/header.php';
?>

    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="heading-page header-text">
    </div>
    <!-- Banner Ends Here -->
    <section class="blog-posts grid-system">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div> 
            <h4>Search for Posts</h4>
            <form id="Posts_search_form" name="posts_from" method="GET" action="#">
                          <input type="text" name="posts_search" class="searchText form-control" placeholder="Type to search for posts..." autocomplete="on">
                        </form>

            </div>
            <br>


            <div class="all-blog-posts">
            <div class="col-lg-12">
              <div class="text-content">
                <h2>Our Recent Blog Entries</h2>
              </div>
              <br>
            </div>
              <div class="row">
              <?php
              foreach ($posts as $post) {
              ?>
                <div class="col-lg-12">
                  <?php include(ROOT_DIR . '\Views\posts-view.php') ?>
                </div>
              <?php
              }
              ?>
              
                <div class="col-lg-12">
                  <ul class="page-numbers">
                     <?php 
                      if ( $page > 1   ){
                    ?>
                      <li><a href=" <?='posts.php?'.$requestQuery.'page='.$page -1 ?>"><i class="fa fa-angle-double-left"></i></a></li>
                    <?php } ?>

                    <?php 
                    for ( $i=$page ; $i <= $numberOfpages ; $i++ ){
                      if ($i==$page + MAX_PAGINATION_PAGES) break;
                    ?>
                       <li <?= ( $i == $page) ? "class='active'" : "" ?> ><a href=" <?='posts.php?'.$requestQuery.'page='.$i?>"><?=$i?></a></li>
                    <?php } ?>

                    <?php 
                      if ( $page + MAX_PAGINATION_PAGES -1 < $numberOfpages  ){
                    ?>
                      <li><a href=" <?='posts.php?'.$requestQuery.'page='.$page+MAX_PAGINATION_PAGES?>"><i class="fa fa-angle-double-right"></i></a></li>
                    <?php } ?>

                  
                    
                    
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">
             
                <div class="col-lg-12">
                  <div class="sidebar-item categories">
                    <div class="sidebar-heading">
                      <h2>Categories</h2>
                    </div>
                  
                    <div class="col-lg-12">
                      <div class="sidebar-item search">
                        <form id="search_category" name="gs" method="GET" action="#">
                          <input type="text" name="catgory_name" class="searchText" placeholder="type to search for category..." autocomplete="on">
                        </form>
                      </div>
                      </div>
                      <br>
                    <div class="content">
                      <ul>
                      <?php
                         foreach ($catgories as $catg){
                        ?>
                          <li><a href=" <?='posts.php?category_id='.$catg->id?>"> - <?=$catg->name?></a></li>
                        <?php 
                         } ?>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item tags">
                    <div class="sidebar-heading">
                      <h2>Tags</h2>
                    </div>
                    <div class="col-lg-12">
                      <div class="sidebar-item search">
                        <form id="search_tags" name="gs" method="GET" action="#">
                          <input type="text" name="tag_name" class="searchText" placeholder="type to search for Tags..." autocomplete="on">
                        </form>
                      </div>
                      </div>
                      <br>
                    <div class="content">
                      <ul>
                      <?php
                         foreach ($tags as $tag){
                        ?>
                          <li><a href=" <?='posts.php?tag_id='.$tag->id?>"> <?=$tag->name?></a></li>
                        <?php 
                         }
                        ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <?php 
    require_once 'layouts/footer.php';
    ?>