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
        $this->likesCont = $likesCont;
    }

    function set_tags($tags) {
        $this->tags = $tags;
    }

    function set_image($image) {
        $this->image = $image;
    }
    
}