<?php 
require_once ('config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\CategoryController.php');
require_once (ROOT_DIR.'\controllers\TagController.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
define('NUMBER_OF_POSTS_Banner_PAGE' , 6);
define('NUMBER_OF_POSTS_MAIN_PAGE' , 3);
define('NUMBER_OF_USERS_MAIN_PAGE' , 3);
define('NUMBER_OF_CATAGORIES_MAIN_PAGE' , 6);
define('NUMBER_OF_TAGS_MAIN_PAGE' , 20);
$postCont = new PostController ();
$posts = $postCont->getLatestPosts(NUMBER_OF_POSTS_MAIN_PAGE);
$postsBanner = $postCont->getLatestPostsPerCategory(NUMBER_OF_POSTS_Banner_PAGE);

$userCont = new UserController  ();
$usersPerPosts = $userCont->getActiveUsers(NUMBER_OF_USERS_MAIN_PAGE);
$catCont = new CategoryController ();
$catgories = $catCont->getcategories(NUMBER_OF_CATAGORIES_MAIN_PAGE , null );
$tagCont = new TagController ();
$tags = $tagCont->getTags(NUMBER_OF_TAGS_MAIN_PAGE, null);

require_once 'layouts/header.php';
?>
    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="main-banner header-text">
      <div class="container-fluid">
        <div class="owl-banner owl-carousel">
        
          <div class="item">
            <img src="assets/images/banner-item-01.jpg" alt="">
            <div class="item-content">
              <div class="main-content">
                <div class="meta-category">
                  <span>Fashion</span>
                </div>
                <a href="post-details.html"><h4>Morbi dapibus condimentum</h4></a>
                <ul class="post-info">
                  <li><a href="#">Admin</a></li>
                  <li><a href="#">May 12, 2020</a></li>
                  <li><a href="#">12 Comments</a></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="item">
            <img src="assets/images/banner-item-02.jpg" alt="">
            <div class="item-content">
              <div class="main-content">
                <div class="meta-category">
                  <span>Nature</span>
                </div>
                <a href="post-details.html"><h4>Donec porttitor augue at velit</h4></a>
                <ul class="post-info">
                  <li><a href="#">Admin</a></li>
                  <li><a href="#">May 14, 2020</a></li>
                  <li><a href="#">24 Comments</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/banner-item-03.jpg" alt="">
            <div class="item-content">
              <div class="main-content">
                <div class="meta-category">
                  <span>Lifestyle</span>
                </div>
                <a href="post-details.html"><h4>Best HTML Templates on TemplateMo</h4></a>
                <ul class="post-info">
                  <li><a href="#">Admin</a></li>
                  <li><a href="#">May 16, 2020</a></li>
                  <li><a href="#">36 Comments</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/banner-item-04.jpg" alt="">
            <div class="item-content">
              <div class="main-content">
                <div class="meta-category">
                  <span>Fashion</span>
                </div>
                <a href="post-details.html"><h4>Responsive and Mobile Ready Layouts</h4></a>
                <ul class="post-info">
                  <li><a href="#">Admin</a></li>
                  <li><a href="#">May 18, 2020</a></li>
                  <li><a href="#">48 Comments</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/banner-item-05.jpg" alt="">
            <div class="item-content">
              <div class="main-content">
                <div class="meta-category">
                  <span>Nature</span>
                </div>
                <a href="post-details.html"><h4>Cras congue sed augue id ullamcorper</h4></a>
                <ul class="post-info">
                  <li><a href="#">Admin</a></li>
                  <li><a href="#">May 24, 2020</a></li>
                  <li><a href="#">64 Comments</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="assets/images/banner-item-06.jpg" alt="">
            <div class="item-content">
              <div class="main-content">
                <div class="meta-category">
                  <span>Lifestyle</span>
                </div>
                <a href="post-details.html"><h4>Suspendisse nec aliquet ligula</h4></a>
                <ul class="post-info">
                  <li><a href="#">Admin</a></li>
                  <li><a href="#">May 26, 2020</a></li>
                  <li><a href="#">72 Comments</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Banner Ends Here -->


    <section class="blog-posts">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="all-blog-posts">
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
                  <div class="main-button">
                    <a href="posts.php">View All Posts</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">
                <div class="col-lg-12">
                  <div class="sidebar-item search">
                    <form id="search_form" name="gs" method="GET" action="posts.php">
                      <input type="text" name="posts_search" class="searchText" placeholder="type to search for post..." autocomplete="on">
                    </form>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item recent-posts">
                    <div class="sidebar-heading">
                      <h2>Out Active Blogers</h2>
                    </div>
                    <div class="content">
                      <ul>
                        <?php 
                        foreach ($usersPerPosts as $user){
                        ?>
                        <li><a href="<?= "posts.php?user_id=". $user->id ?>">
                          <h5><?=$user->name?></h5>
                          <span><?=$user->postsCount ." Posts"?></span>
                        </a></li>
                        <?php } ?>

                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item categories">
                    <div class="sidebar-heading">
                      <h2>Categories</h2>
                    </div>
                    <div class="content">
                      <ul>
                        <?php
                         foreach ($catgories as $catg){
                        ?>
                          <li><a href=" <?='posts.php?category_id='.$catg->id?>"> - <?=$catg->name?></a></li>
                        <?php 
                         }
                        ?>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item tags">
                    <div class="sidebar-heading">
                      <h2>Tag Clouds</h2>
                    </div>
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
    include 'layouts/footer.php';
    ?>