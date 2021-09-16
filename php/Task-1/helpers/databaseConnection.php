<?php
class DatabaseConnection {

    public $server = 'localhost';
    public $database = 'blog' ;
    public $account='root';
    public $password='';
    public $conn;
 

    function __construct() {
        $this->conn = mysqli_connect($this->server, $this->account, $this->password, $this->database);
      }

      function get_connection() {
        return $this->conn;
      }


    function __destruct() {
        mysqli_close($this->conn);
    }

}