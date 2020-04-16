<?php

include_once('database/php/data/core/code.database.data.core.class_names.php');
include_once('database/data/db_data_test_helper.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\database\data\core\CODE_DATABASE_DATA__CORE__CLASS_NAMES;
///////////////////////////////////////////////////////////////////////////////////////////////////

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();
 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CORE__CLASS_NAMES::CORE_OBJECT);

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
