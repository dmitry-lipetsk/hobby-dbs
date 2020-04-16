<?php

include_once('test_services.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 for($n=1;$n!=4;++$n)
 {
  TEST_SERVICES::log('--------------------------- ',$n,'.',PHP_EOL);

  $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

  TEST_SERVICES::PGSQL_TR__rollback($tr);

  unset($tr);
 }//for $n

 unset($cn);
}
catch(Exception $e)
{
 TEST_SERVICES::print_error_exception($e);

 exit(1);
}//catch

exit(0);
?>
