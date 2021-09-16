<?php
class Tag {
    public $id;
    public $name;
    function __construct($id, $name) {
        $this->name = $name;
        $this->id = $id;
      }
}