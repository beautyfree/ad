<?php

/*
Our models extend this class
The $is_valid property is set to true when an object is successfully instantiated
*/

class ActiveRecord {
    private $aData = array();

    public $is_valid = false;


    public function __get($sName) {
        if (array_key_exists($sName, $this->aData)) {
            return $this->aData[$sName];
        }
    }
}
