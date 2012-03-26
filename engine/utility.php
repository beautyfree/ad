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
    include_once("../app/views/404.tpl");
    exit;
}

function fatal_error($error) {
    die("Something went wrong: $error");
}


function __autoload($sClass) {
    $sNameClass = strtolower($sClass);

    if (file_exists("/var/www/ad/app/models/$sNameClass.php")) {
        require_once "/var/www/ad/app/models/$sNameClass.php";

        return;
    } else {
        $aNameClass = explode("_", $sClass);
        if (sizeof($aNameClass) > 1) {
            $sNameClass = implode(DIRECTORY_SEPARATOR, $aNameClass) . '.php';
            require_once $sNameClass;

            return;
        }
    }

    fatal_error("Cannot find class '$sClass'");
}
