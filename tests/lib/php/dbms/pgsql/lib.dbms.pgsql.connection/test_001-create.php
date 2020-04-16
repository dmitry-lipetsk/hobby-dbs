<?php

include_once('test_services.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 unset($cn);
}
catch(Exception $e)
{
 TEST_SERVICES::print_error_exception($e);

 exit(1);
}//catch

exit(0);
?>