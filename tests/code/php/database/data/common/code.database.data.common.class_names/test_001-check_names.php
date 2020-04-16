<?php

include_once('database/php/data/common/code.database.data.common.class_names.php');
include_once('database/data/db_data_test_helper.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\database\data\common\CODE_DATABASE_DATA__COMMON__CLASS_NAMES;
///////////////////////////////////////////////////////////////////////////////////////////////////

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();
 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__COMMON__CLASS_NAMES::COMMON_EMPLOYER);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__COMMON__CLASS_NAMES::COMMON_CURRENCY);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__COMMON__CLASS_NAMES::COMMON_CUSTOMER);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__COMMON__CLASS_NAMES::COMMON_ROBOT);

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
