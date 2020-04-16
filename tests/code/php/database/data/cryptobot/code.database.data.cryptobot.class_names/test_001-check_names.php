<?php

include_once('database/php/data/cryptobot/code.database.data.cryptobot.class_names.php');
include_once('database/data/db_data_test_helper.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\database\data\cryptobot\CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES;
///////////////////////////////////////////////////////////////////////////////////////////////////

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();
 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_COIN);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_MARKET);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_PRICE);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_PRICE_HISTORY);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_MARKET_ITEM_NAME);

 DB_DATA_TEST_HELPER::check_class_name
  ($tr,
   CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_AMOUNT_HISTORY);

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
