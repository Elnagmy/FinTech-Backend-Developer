<?php
class Post {
    public $id;
    public $title;
    public $content;
    public $publish_date;
    public $created_at;
    public $updated_at;
    public $category;
    public $author;
    public $image;
    public $comments;
    public $commentsCont;
    public $likesCont;
    public $tags;
    public $postCnt;
    public $likes_count;
    public $liked_by_me;
    public $category_id;
    
    function __construct($id, $title ,$content, $publish_date , $category, $author) {
      $this->title = $title;
      $this->id = $id;
      $this->publish_date = $publish_date;
      $this->category = $category;
      $this->author = $author;
      $this->content = $content;
    }


    function set_commentsCont($cont) {
        $this->commentsCont = $cont;
      }

    function set_likesCont($likesCont) {
        $this->likes_count = $likesCont;
    }

    function set_tags($tags) {
        $this->tags = $tags;
    }

    function set_comments($comments) {
        $this->comments = $comments;
    }

    function set_image($image) {
        $this->image = $image;
    }

    function set_liked_by_me ($liked_by_me){
        $this->liked_by_me = $liked_by_me;
    }

    
    
}