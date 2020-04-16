<?php

include_once('test_services.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $r=TEST_SERVICES::PGSQL_TR__query
  ($tr,
   'select ID from CORE.CLASSES where name=$1',
   'CORE.OBJECT');

 if(!($row=TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r)))
  throw new Exception('Row not found!');

 TEST_SERVICES::log_line('OK. row was selected. class id: '.$row['id']);

 if($row['id']!=='0')
  throw new Exception('WRONG CLASS ID ['.gettype($row['id']).']! Expected 0.');

 unset($r);

 TEST_SERVICES::PGSQL_TR__commit($tr);

 unset($tr);
 unset($cn);
}
catch(Exception $e)
{
 TEST_SERVICES::print_error_exception($e);

 exit(1);
}//catch

exit(0);
?>
