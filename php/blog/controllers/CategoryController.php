<?php
require_once ('config.php');
require_once(ROOT_DIR.'\beens\Category.php');
require_once (ROOT_DIR.'\models\CategoryModel.php');
class CategoryController {

    function getcategories($count) {
        $model = new CategoryModel();
        return $model->getcategories($count);
       }

}