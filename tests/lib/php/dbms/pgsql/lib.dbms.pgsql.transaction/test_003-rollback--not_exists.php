<?php

include_once('test_services.php');
include_once('check_errors.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $cn->internal__rollback_transaction($tr);

 for(;;)
 {
  try
  {
   TEST_SERVICES::PGSQL_TR__rollback($tr);
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__BUG_CHECK__CANT_ROLLBACK_TR__TR_NOT_EXISTS($e);

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
