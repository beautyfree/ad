<?php

$start_time = microtime(true);

require_once '../engine/bootstrap.php';
Router::route();

$exec_time = microtime(true) - $start_time;
echo $exec_time;
