<?php

require_once (ROOT_DIR.'\beens\Category.php');
class CategoryModel  extends Model{

    function getcategories($count , $cat_name= null ) {
        $result =[] ; 
        $SQL = 'SELECT c.* from categories as c order by c.name asc LIMIT '.$count; 
        if (  $cat_name !=null ) {
            $SQL = "SELECT c.* from categories as c where  c.name like '%".$cat_name."%'" ;
        }
     
        $returnedResul =  parent::getRows($SQL);
        foreach ($returnedResul as $row){
            $category = new Category($row['id'], $row['name']);
            array_push($result,  $category);
        }
        return  $result ;   
       }


    function getAllCategories() {
    $sql = "SELECT * FROM categories";
    return parent::getRows($sql);
    }

}