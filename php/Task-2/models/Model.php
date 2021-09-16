<?php
require_once ('config.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');

class Model {


    function getRows($sql){
    $database = new DatabaseConnection ();
    $conn = $database -> get_connection();
    $results = [];
    
    if ($conn) {
        $query = mysqli_query($conn, $sql);
        while (($row = mysqli_fetch_assoc($query)) != null) {
            array_push($results, $row);
          
        }
    }
    return $results;
    }

    function getRow($sql){
        $results = $this->getRows($sql);
        if (count($results) > 0)
            return $results[0];
        return null;
    }

    function addData($sql){
    }

    function editData($sql){
    }

    function deleteData($sql){
    }
}