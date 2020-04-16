<?php

include_once('common/php/code.common.dbclass_manager.php');
include_once('database/php/data/core/code.database.data.core.table_names.php');
include_once('test_services.php');
include_once('check_errors.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\common\CODE_COMMON__DBCLASS_MANAGER;
///////////////////////////////////////////////////////////////////////////////////////////////////

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();
 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $mng=new CODE_COMMON__DBCLASS_MANAGER($tr);

 for(;;)
 {
  try
  {
   $mng->get_class_by_id(-1999);
  }
  catch(Exception $e)
  {
   TEST_SERVICES::print_exception_ok($e);

   CHECK_ERRORS::check_exc__DBCLASS_MNG_ERR__DBCLASS_ID_NOT_FOUND($e,-1999);

   break;
  }//catch

  TEST_SERVICES::throw_we_wait_error();
 }//for[ever]

 TEST_SERVICES::log_line();

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
