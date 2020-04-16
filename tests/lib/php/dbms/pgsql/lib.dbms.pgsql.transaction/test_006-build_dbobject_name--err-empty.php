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
   $tr->build_dbobject_name(); //throw!!!
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__COMMON_ERROR__EMPTY_DATABASE_OBJECT_NAME($e);

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
1