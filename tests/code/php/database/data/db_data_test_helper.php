<?php

include_once('database/php/data/core/code.database.data.core.table_names.php');
include_once('test_services.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\database\data\core\CODE_DATABASE_DATA__CORE__TABLE_NAMES;
///////////////////////////////////////////////////////////////////////////////////////////////////
//class DB_DATA_TEST_HELPER

class DB_DATA_TEST_HELPER
{
 public static function check_class_name($tr,$class_name)
 {
  TEST_SERVICES::log_line('check class name ['.$class_name.'] ...');
  
  $r=TEST_SERVICES::PGSQL_TR__query
     ($tr,
      'select id from '.CODE_DATABASE_DATA__CORE__TABLE_NAMES::CORE_CLASSES.' where name=$1',
      $class_name);
  
  if(!($row=TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r)))
   throw new Exception('class not found!');
  
  TEST_SERVICES::log_line('class id: ',$row['id']);
  
  if(($row=TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r)))
   throw new Exception('multiple definition of class!');
  
  unset($r);
 }//check_class_name
};//class DB_DATA_TEST_HELPER

///////////////////////////////////////////////////////////////////////////////////////////////////
