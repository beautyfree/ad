<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
// set inc path
$paths = array(
    dirname(dirname(__FILE__)) . '/app', get_include_path(), dirname(__FILE__));
set_include_path(implode(PATH_SEPARATOR, $paths));
*/


$paths = implode(PATH_SEPARATOR, array(
    '/var/www/ad/engine/lib',
    '/var/www/ad/app'
));
set_include_path($paths); unset($paths);
chdir(dirname(__FILE__));

require_once('action_controller.php');
require_once('../app/controllers/application_controller.php');
require_once('action_view.php');
require_once('active_record.php');

require_once('dispatcher.php');
require_once('utility.php');
require_once('router.php');

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');

session_start();
