<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// set inc path
$paths = array(
    dirname(dirname(__FILE__)) . '/app',
    get_include_path(),
    dirname(__FILE__)
);
set_include_path(implode(PATH_SEPARATOR, $paths));
unset($paths);

chdir (dirname(__FILE__));

require_once('helpers/utility.php');
require_once('helpers/router.php');
//require_once('../app/config/config.php');
require_once('controllers/controller.php');
require_once('../app/config/routes.php');



mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');

session_start();
