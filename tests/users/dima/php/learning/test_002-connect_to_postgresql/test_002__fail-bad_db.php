<?php

include_once('db.php');

echo 'try connect to PG'.PHP_EOL;

$db=DB::create('localhost','bigdata_test12345','postgres','pg');

echo 'OK'.PHP_EOL;

unset($db);

echo 'EXIT'.PHP_EOL;
?>
