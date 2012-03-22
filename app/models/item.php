<?php

class Item extends ActiveRecord {

    public $id = "";
    public $name = "";

    function __construct($id="") {
        //Normally you'd connect to the database to fetch the object's properties here
        if ($id != "") {
            $this->id = $id;
            $this->name = "#$this->id";
            $this->is_valid = true;
        }
    }

}
