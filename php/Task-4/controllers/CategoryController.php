<?php

require_once(ROOT_DIR.'\beens\Category.php');
require_once (ROOT_DIR.'\models\CategoryModel.php');
class CategoryController {

    function getcategories($count , $cat_name) {
        $model = new CategoryModel();
        return $model->getcategories($count , $cat_name);
       }

    function getAllCategories() {
        $model = new CategoryModel();
        return $model->getAllCategories();
    }

}