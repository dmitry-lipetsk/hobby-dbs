<?php

include_once('test_services.php');
include_once('check_errors.php');

try
{
 $test_data=array
  (
   array(null),
   array(''),
   array('a',null),
   array('a','')
  );

 $cn=TEST_SERVICES::PGSQL_CN__create();

 for($i=0;$i!=count($test_data);++$i)
 {
  $nparts=$test_data[$i];

  TEST_SERVICES::log_line('--------------------------- ',$i,'. |'.implode(',',$nparts).'|');

  try
  {
   $cn->build_dbobject_name(... $nparts); //throw!!!
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__COMMON_ERROR__WRONG_DATABASE_OBJECT_NAME_STRUCTURE($e);

   continue;
  }//catch

  TEST_SERVICES::throw_we_wait_error();
 }//for $i

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