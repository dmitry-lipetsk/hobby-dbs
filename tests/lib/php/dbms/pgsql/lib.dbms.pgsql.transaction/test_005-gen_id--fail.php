<?php

include_once('test_services.php');
include_once('check_errors.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 for(;;)
 {
  try
  {
   TEST_SERVICES::PGSQL_TR__gen_id
    ($tr,
     'COMMON.SEQ_EMPLOYEE+++');
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__GENID_ERR__FAILED_TO_GENERATE_ID($e,'COMMON.SEQ_EMPLOYEE+++');

   CHECK_ERRORS::check_exc__CMD_ERR__FAILED_TO_EXECUTE_QUERY($e->getPrevious());

   break;
  }//catch

  TEST_SERVICES::throw_we_wait_error();
 }//for[ever]

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
