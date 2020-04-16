<?php

namespace am\code\database\services\core{
///////////////////////////////////////////////////////////////////////////////////////////////////
include_once('database/php/data/core/code.database.data.core.table_names.php');
///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\database\data\core\CODE_DATABASE_DATA__CORE__TABLE_NAMES;
//////////////////////////////////////////////////////////////////////////////////////////////////
//class CODE_DATABASE_SERVICES__CORE

class CODE_DATABASE_SERVICES__CORE
{
 public static function select_tables($tr)
 {
  $sql
   ='select t.ID,'.PHP_EOL
          .'t.PARENT_ID,'.PHP_EOL
          .'t.TABLE_NAME,'.PHP_EOL
          .'t.TABLE_SCHEMA'.PHP_EOL
   .'from '.CODE_DATABASE_DATA__CORE__TABLE_NAMES::CORE_TABLE_NAMES.' t'.PHP_EOL;

  $r=$tr->query($sql);

  return $r;
 }//select_tables

 //-----------------------------------------------------------------------
 public static function select_classes($tr)
 {
  $sql
   ='select c.ID,'.PHP_EOL
          .'c.NAME,'.PHP_EOL
          .'c.TABLE_ID,'.PHP_EOL
          .'c.SEQ_SCHEMA,'.PHP_EOL
          .'c.SEQ_NAME'.PHP_EOL
   .'from '.CODE_DATABASE_DATA__CORE__TABLE_NAMES::CORE_CLASSES.' c'.PHP_EOL;

  $r=$tr->query($sql);

  return $r;
 }//select_classes
};//class CODE_DATABASE_SERVICES__CORE

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\code\database\services\core
?>
