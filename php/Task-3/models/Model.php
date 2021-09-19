<?php
require_once ('config.php');
require_once  (ROOT_DIR.'\helpers\databaseConnection.php');

class Model {


    function getRows($sql ,  $types = null, $vals = null){
    $database = new DatabaseConnection ();
    $conn = $database -> get_connection();
    $results = [];
   
    if ($conn) {
        if ($types && $vals) {
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, $types, ...$vals);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $query = mysqli_query($conn, $sql);
        }
   
        while (($row = mysqli_fetch_assoc($query)) != null) {
            array_push($results, $row);
        }
    }
    return $results;
    }

    function getRow($sql, $types = null, $vals = null){
        $results = $this->getRows($sql, $types , $vals);

        if (count($results) > 0)
            return $results[0];
        return null;
    }

    function addData($sql, $types = null, $vals = null){
        var_dump($sql );
        var_dump($types );
        var_dump($vals );
        //die();
        $database = new DatabaseConnection ();
        $conn = $database -> get_connection();
        if ($conn) {
            if ($types && $vals) {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $types, ...$vals);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                mysqli_query($conn, $sql);
            }
        }
        $lastId = mysqli_insert_id($conn);

        return $lastId;
    }

    function editData($sql, $types = null, $vals = null){
 
        $database = new DatabaseConnection ();
        $conn = $database -> get_connection();
        $isSuccess = false;
        if ($conn) {
            if ($types && $vals) {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $types, ...$vals);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $isSuccess = true;
            } else {
                mysqli_query($conn, $sql);
                $isSuccess = true;
            }
        }

        return $isSuccess;
    }

    function deleteData($sql , $types = null , $vals = null){
        $database = new DatabaseConnection ();
        $conn = $database -> get_connection();
        $isSuccess = false;
        if ($conn) {
            if ($types && $vals) {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $types, ...$vals);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $isSuccess = true;
            } else {
                mysqli_query($conn, $sql);
                $isSuccess = true;
            }
        }
        return $isSuccess;
    }
}