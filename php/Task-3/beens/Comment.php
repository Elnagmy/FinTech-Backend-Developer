<?php
class Comment {
    public $id;
    public $comment;
    public $comment_date;
    public $author;

    function __construct($id, $comment , $comment_date=null , $author =null) {
        $this->comment = $comment;
        $this->id = $id;
        $this->comment_date = $comment_date;
        $this->author = $author;
      }
}