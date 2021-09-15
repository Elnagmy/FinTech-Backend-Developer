<?php 
require_once(ROOT_DIR.'\beens\Tag.php');
class TagModel extends Model {
    function getTags($count) {
        $result =[] ; 
        $SQL = 'SELECT t.* from Tags as t order by t.name asc LIMIT '.$count; 
        $returnedResul =  parent::getRows($SQL);
        foreach ($returnedResul as $row){
            $tag = new Tag($row['id'], $row['name']);
            array_push($result,  $tag);
        }
        return  $result ;   
       }
}