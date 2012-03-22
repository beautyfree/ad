<?php

function render($sTemplatePath) {
    $aPath = explode('/',$sTemplatePath);

    if(count($aPath)==1) {
        $sTemplatePath = ActionController::GetControllerPath().'/'.$sTemplatePath;
    }
    ActionController::SetTemplateAction($sTemplatePath);
}

function error_404() {
    header("HTTP/1.0 404 Not Found");
    include_once("../app/views/404.php");
    exit;
}

function fatal_error($error) {
    die("Something went wrong: $error");
}


//automatically load classes from the models folder
//see -> http://us3.php.net/manual/en/language.oop5.autoload.php
function __autoload($sClass) {
    $sClass = strtolower($sClass);
    if (file_exists("/var/www/ad/app/models/$sClass.php")) {
        require_once("/var/www/ad/app/models/$sClass.php");
        return;
    }

    fatal_error("Cannot find class '$sClass'");
}
