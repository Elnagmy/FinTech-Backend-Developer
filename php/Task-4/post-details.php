<?php 
require_once ('config.php');
require_once (ROOT_DIR.'\controllers\PostController.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
require_once (ROOT_DIR.'\helpers\Vaildators.php');
$postCont = new PostController ();
$userController = new UserController ();
$vaildator= new Vaildator ();
$post_id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : 1;
$comment = isset($_REQUEST['comment']) ? (filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
$formIssubmited=  isset($_REQUEST["formIssubmited"] ) ? true : false ;
$anonymous = $userController->getUserIdFromSession() == null ? true : false ;
$autorizedToDeleteComment = false;
if( $formIssubmited && !$anonymous ) { 
  $post = $postCont->addComent($post_id , $userController->getUserIdFromSession() , $comment);
  header("location:post-details.php?post_id=".$post_id);
}

if($post_id) {
  $post = $postCont->getPostById($post_id);
 
}



require_once 'layouts/header.php';
?>

    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="heading-page header-text">
      <section class="page-heading">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-content">
                <h2><?=$post->title?></h2>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <!-- Banner Ends Here -->


    <section class="blog-posts grid-system">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="all-blog-posts">
              <div class="row">
                <div class="col-lg-12">
                  <div class="blog-post">
                    <div class="blog-thumb">
                    <img src="<?= BASE_URL.'/'.$post->image?>" alt="">
                    </div>
                    <div class="down-content">
                      <span><?=$post->category?></span>
                      <a href="post-details.php?post_id="<?=$post->id?>><h4><?=$post->title?></h4></a>
                      <ul class="post-info">
                        <li><a href="#"><?=$post->author->name?></a> </li>
                        <li><a href="#"><?=$post->publish_date?></a></li>
                        <li><a href="#"><?=$post->commentsCont?> Comments</a></li>
                      </ul>
                      <p><?=$post->content?></p>
                      <div class="post-options">
                        <div class="row">
                          <div class="col-6">
                            <ul class="post-tags">
                              <li><i class="fa fa-tags"></i></li>
                              <?php 
                                foreach ($post->tags as $tag ){       
                              ?>
                              <li><a href="posts.php?tag_id=<?=$tag['TAG_ID']?>"><?=$tag['TAG_NAME']?></a>,</li>
                              <?php }?>
                              
                            </ul>
                          </div>
                    
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item comments">
                    <div class="sidebar-heading">
                      <h2><span id="comments_cont_span"><?=$post->commentsCont?> </span> comments</h2>
                    </div>
                    <div class="content">
                      <ul>
                      <?php 
                       
                      if ($post->comments != 0) {
                     
                          foreach ($post->comments as $comment ){  
                          ?>

                            <li id ="Comment_No_<?= $comment->id ?>" style="display: block">
                              <div class="author-thumb">
                                <img src="<?=BASE_URL."\\".$comment->author->pofileImage?>" alt="">
                              </div>
                              <?php if ( $vaildator->isAutorizedToDeleteComment($comment , $post) ) { ?>
                                <button id="delete_comment_btn_<?= $comment->id  ?>" class="btn alert-danger" type="button" onclick="deleteComment(<?= $comment->id ?>)" style=" float: right; display:block">Delete</button>
                                <?php } ?>
                                <div class="right-content">
                                <h4> <?= $comment->author->name?> <span> <?= $comment->comment_date?></span></h4> 
                                
                                <p><?= $comment->comment?></p>
                              </div>
                              <div class="row">
                              <div class="col-md-6">
                                  <span id="likes_comment_count_<?=$comment->id ?>"><?= $comment->likes_count ?></span> Likes
                              </div>
                              <?php if ( !$anonymous ) { ?>

                                    <div class="col-md-6">
                                  
                                  <button id="likes_comment_btn_<?= $comment->id ?>" class="btn" type="button" onclick="likeComment (<?= $comment->id ?>)" style="display:<?= ! $comment->liked_by_me ? "block" : "none" ?>">Like</button>
                                  <button id="unlikes_comment_btn_<?= $comment->id  ?>" class="btn" type="button" onclick="unLikeComment(<?= $comment->id ?>)" style="display:<?= ! $comment->liked_by_me  ? "none" : "block" ?>">UnLike</button>
                                  
                                  </div>
                                <?php } ?>
                              </div>
                            </li>                 
                        <?php }
                     }?>
                    </div>
                  </div>
                </div>
                <?php if ( !$anonymous ) { ?>
                  <div class="col-lg-12">
                    <div class="sidebar-item submit-comment">
                      <div class="sidebar-heading">
                        <h2>Your comment</h2>
                      </div>
                      <div class="content">
                        <form id="comment" action="post-details.php" method="post">
                          <div class="row">
                            <div class="col-lg-12">
                            <input type="hidden" name="post_id" value= "<?= $post->id ?>" \>
                            <input type="hidden" name="formIssubmited" value="true" \>
                              <fieldset>
                                <textarea name="comment" rows="6" id="message" placeholder="Type your comment" required=""></textarea>
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Submit</button>
                              </fieldset>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">
                <div class="col-lg-12">
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item recent-posts">
                    <div class="sidebar-heading">
                      <h2>Post Author</h2>
                    </div>
                    <div class="content">
                    <div class="author-thumb">
                                <img src="<?=BASE_URL."\\".$post->author->pofileImage?>" alt="">
                              </div>
                              <div class="right-content">
                                <h5> <?= $post->author->name?> <span></span></h5>
                                <p><span><?=$post->author->postsCount ." Posts"?></span></p>
                              </div>
                              <div class="row">
                              <div class="col-md-6">
                                  <span id="Followers_count_<?=$post->author->id ?>"><?= $post->author->followers_count?></span> Followers
                              </div>
                              <?php if ( !$anonymous ) { ?>
                                  <div class="col-md-6">
                                  <button id="follow_btn_<?= $post->author->id?>" class="btn" type="button" onclick="follow (<?= $post->author->id ?>)" style="display:<?= ! $post->author->followed_by_me ? "block" : "none" ?>">Follow</button>
                                  <button id="unfollow_btn_<?= $post->author->id ?>" class="btn" type="button" onclick="unFollow(<?= $post->author->id?>)" style="display:<?= ! $post->author->followed_by_me ? "none" : "block" ?>">Un-Follow</button>
                                  </div>
                              <?php } ?>
                              </div>
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