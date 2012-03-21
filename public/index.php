<?php

$start_time = microtime(true);




require_once '../engine/bootstrap.php';

$oDispatcher = new Dispatcher();
$oDispatcher->dispatch();




$exec_time = microtime(true) - $start_time;
echo printf("%.5f",$exec_time);
