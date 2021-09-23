<?php
require_once('../config.php');
require_once (ROOT_DIR.'\models\CommentModel.php');

class CommentController {

    function likeComment($id, $userId) {
        $commentModel = new CommentModel ();
        return $commentModel->likeComment($id, $userId) ;
    }

    function unLikeComment($id, $userId) {
        $commentModel = new CommentModel ();
        $commentModel->unLikeComment($id, $userId) ;
    }

    function getCommentbyId($id) {
        $commentModel = new CommentModel ();
        return $commentModel->getCommentbyId($id) ;
    }

    function DeleteCommentbyId($id) {
        $commentModel = new CommentModel ();
        return $commentModel->DeleteCommentbyId($id) ;
    }
    
    
}