<?php

include_once('test_services.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $r=TEST_SERVICES::PGSQL_TR__gen_id
  ($tr,
   'COMMON.SEQ_EMPLOYEE');

 TEST_SERVICES::log_line('OK. identifier was generated. id: '.$r);

 if(!$r)
  throw new Exception('WRONG ID!');

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
