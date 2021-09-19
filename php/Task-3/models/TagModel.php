<?php 
require_once(ROOT_DIR.'\beens\Tag.php');
class TagModel extends Model {
    function getTags($count , $tag_name = null) {
        $result =[] ; 
        $SQL = 'SELECT t.* from Tags as t order by t.name asc LIMIT '.$count; 
        if ( $tag_name != null ){
            $SQL = "SELECT t.* from Tags as t where  t.name like '%".$tag_name."%'" ;
        }

        $returnedResul =  parent::getRows($SQL);
        foreach ($returnedResul as $row){
            $tag = new Tag($row['id'], $row['name']);
            array_push($result,  $tag);
        }
        return  $result ;   
       }

    function getAllTages (){
        $sql = "SELECT * FROM tags";
        return parent::getRows($sql);
    }
}