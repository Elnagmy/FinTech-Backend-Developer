<?php
require_once ('config.php');
require_once (ROOT_DIR.'\models\TagModel.php');
class TagController {
    function getTags($count) {
        $model = new TagModel();
        return $model->getTags($count);
       }

}