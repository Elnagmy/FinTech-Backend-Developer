<?php
class User {
  public $id;
  public $name;
  public $userName;
  public $password;
  public $email;
  public $phone;

  function __construct($id, $name , $userName , $password, $email , $phone) {
    $this->name = $name;
    $this->id = $id;
    $this->userName = $userName;
    $this->password = $password;
    $this->email = $email;
    $this->phone = $phone;
  }
  
  function get_name() {
    return $this->name;
  }
}
