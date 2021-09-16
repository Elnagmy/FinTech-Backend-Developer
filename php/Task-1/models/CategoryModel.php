<?php
require_once (ROOT_DIR.'\beens\Category.php');
class CategoryModel  extends Model{

    function getcategories($count) {
        $result =[] ; 
        $SQL = 'SELECT c.* from categories as c order by c.name asc LIMIT '.$count; 
        $returnedResul =  parent::getRows($SQL);
        foreach ($returnedResul as $row){
            $category = new Category($row['id'], $row['name']);
            array_push($result,  $category);
        }
        return  $result ;   
       }

}