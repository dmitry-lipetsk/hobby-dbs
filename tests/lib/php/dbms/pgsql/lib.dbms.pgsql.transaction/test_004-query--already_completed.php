<?php

include_once('test_services.php');
include_once('check_errors.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 TEST_SERVICES::PGSQL_TR__commit($tr);

 for(;;)
 {
  try
  {
   TEST_SERVICES::PGSQL_TR__query
    ($tr,
     'select ID123 from CORE.CLASSES where name=$1',
     'CORE.OBJECT');
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__TR_ERR__CANT_EXECUTE_QUERY__ALREADY_COMPLETED($e);

   break;
  }//catch

  TEST_SERVICES::throw_we_wait_error();
 }//for[ever]

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
