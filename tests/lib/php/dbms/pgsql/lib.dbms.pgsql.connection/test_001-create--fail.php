<?php

include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.connection.php');
include_once('test_services.php');
include_once('check_errors.php');
include_once('test_cfg.php');

use am\lib\dbms\pgsql\PGSQL_CONNECTION;

try
{
 for(;;)
 {
  try
  {
   $cn=PGSQL_CONNECTION::create
       (TEST_CFG::c_pg_server,
        TEST_CFG::c_pg_database,
        TEST_CFG::c_pg_user_id,
        'BLA-BLA-BLA');
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__CN_ERR__FAILED_TO_CONNECT($e);

   break;
  }//catch

  TEST_SERVICES::throw_we_wait_error();
 }//for[ever]
}
catch(Exception $e)
{
 TEST_SERVICES::print_error_exception($e);

 exit(1);
}//catch

exit(0);
?>
