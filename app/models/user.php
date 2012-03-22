<?php

class User extends ActiveRecord {

    public $username = "";
    public $name = "";

    function __construct($username="") {
        //Normally you'd connect to the database to fetch the object's properties here
        if ($username != "") {
            $this->username = $username;
            $this->name = "$username Smith";
            $this->is_valid = true;
        }
    }

}
