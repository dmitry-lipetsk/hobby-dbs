<?php

namespace am\code\common{
///////////////////////////////////////////////////////////////////////////////////////////////////
include_once('common/php/code.common.dbtable_descr.php');
include_once('common/php/code.common.dbclass_descr.php');
include_once('common/php/code.common.error_messages.php');
include_once('database/php/services/core/code.database.services.core.php');
///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\database\services\core\CODE_DATABASE_SERVICES__CORE;
use Exception;
///////////////////////////////////////////////////////////////////////////////////////////////////
//class CODE_COMMON__DBCLASS_MANAGER

class CODE_COMMON__DBCLASS_MANAGER
{
 public function __construct($tr)
 {
  $t=self::helper__load_tables($tr);

  $classes_by_id   =null;
  $classes_by_name =null;

  $c=self::helper__load_classes
     ($tr,
      $t,
      $classes_by_id,
      $classes_by_name);

  $this->m_tables_by_id     =$t;
  $this->m_classes_by_id    =$classes_by_id;
  $this->m_classes_by_name  =$classes_by_name;
 }//__construct

 //interface -------------------------------------------------------------
 public function get_class_by_id($class_id)
 {
  if(!$this->m_classes_by_id)
   self::helper__throw_err__object_not_init();

  if(!array_key_exists($class_id,$this->m_classes_by_id))
   self::helper__throw_err__class_id_not_found($class_id);

  return $this->m_classes_by_id[$class_id];
 }//get_class_by_id

 //-----------------------------------------------------------------------
 public function get_class_by_name($class_name)
 {
  if(!$this->m_classes_by_name)
   self::helper__throw_err__object_not_init();

  if(!array_key_exists($class_name,$this->m_classes_by_name))
   self::helper__throw_err__class_name_not_found($class_name);

  return $this->m_classes_by_name[$class_name];
 }//get_class_by_name

 //helper methods --------------------------------------------------------
 private static function helper__load_tables($tr)
 {
  $r=CODE_DATABASE_SERVICES__CORE::select_tables($tr); //throw

  $tables1_by_id=array();

  while($row=$r->fetch_assoc())
  {
   $table_name
    =$tr->build_dbobject_name($row['table_schema'],$row['table_name']);

   $table
    =new CODE_COMMON__DBTABLE_DESCR
         ($row['id'],
          $table_name,
          $row['parent_id']);

   $tables1_by_id[$table->get_table_id()]=$table;
  }//while $row

  //rebuild tables [link descrs] -------
  $tables2_by_id=array();

  foreach($tables1_by_id as $t1)
  {
   //build stack
   $stack=array();

   $base=null;

   for(;;)
   {
    if(array_key_exists($t1->get_table_id(),$tables2_by_id))
    {
     $base=$t1;
     break;
    }//if

    if(array_key_exists($t1->get_table_id(),$stack))
    {
     self::helper__throw_bug_check__recursion_in_database_table_list
       ($t1->get_table_id());
    }//if

    $stack[]=$t1;

    if($t1->get_parent()===null)
     break;

    if(!array_key_exists($t1->get_parent(),$tables1_by_id))
    {
     self::helper__throw_bug_check__unexpected_parent_table_id
      ($t1->get_table_id(),
       $t1->get_parent());
    }//if

    $t1=$tables1_by_id[$t1->get_parent()];
   }//for[ever]

   //process stack
   for($i=count($stack);;)
   {
    if($i==0)
     break;

    --$i;

    $t2=$stack[$i];

    if(!$base)
    {
     $base=$t2;
    }//if
    else
    {
     $base
      =new CODE_COMMON__DBTABLE_DESCR
         ($t2->get_table_id(),
          $t2->get_table_name(),
          $base);
    }//else

    $tables2_by_id[$base->get_table_id()]=$base;

    unset($t2);
   }//for $i

   unset($stack);
  }//foreach $t1

  return $tables2_by_id;
 }//helper__load_tables

 //-----------------------------------------------------------------------
 private static function helper__load_classes($tr,
                                              $tables_by_id,
                                              &$result__classes_by_id,
                                              &$result__classes_by_name)
 {
  $result__classes_by_id=array();
  $result__classes_by_name=array();

  $r=CODE_DATABASE_SERVICES__CORE::select_classes($tr); //throw

  while($row=$r->fetch_assoc())
  {
   //var_dump($row);

   $table_id=$row['table_id'];

   if($table_id===null)
   {
    self::helper__throw_bug_check__database_class_not_link_to_table
     ($row['id']);
   }//if

   if(!array_key_exists($table_id,$tables_by_id))
   {
    self::helper__throw_bug_check__database_table_not_found
     ($table_id);
   }//if

   $table_descr
    =$tables_by_id[$table_id];

   $class_seq_name
    =null;

   if($row['seq_name'])
    $class_seq_name=$tr->build_dbobject_name($row['seq_schema'],$row['seq_name']);

   $class_descr
     =new CODE_COMMON__DBCLASS_DESCR
          ($row['id'],
           $row['name'],
           $table_descr,
           $class_seq_name);

   $result__classes_by_id[$class_descr->get_class_id()]
    =$class_descr;

   $result__classes_by_name[$class_descr->get_class_name()]
    =$class_descr;
  }//while $row

  unset($r);
 }//helper__load_classes

 //-----------------------------------------------------------------------
 private static function helper__throw_err__object_not_init()
 {
  throw new Exception(CODE_COMMON__ERROR_MESSAGES::C_DBCLASS_MNG_ERR__OBJECT_NOT_INITED__0);
 }//helper__throw_err__object_not_init

 //-----------------------------------------------------------------------
 private static function helper__throw_err__class_id_not_found($class_id)
 {
  $msg=sprintf
         (CODE_COMMON__ERROR_MESSAGES::C_DBCLASS_MNG_ERR__DBCLASS_ID_NOT_FOUND__1,
          $class_id);

  throw new Exception($msg);
 }//helper__throw_err__class_id_not_found

 //-----------------------------------------------------------------------
 private static function helper__throw_err__class_name_not_found($class_name)
 {
  $msg=sprintf
         (CODE_COMMON__ERROR_MESSAGES::C_DBCLASS_MNG_ERR__DBCLASS_NAME_NOT_FOUND__1,
          $class_name);

  throw new Exception($msg);
 }//helper__throw_err__class_name_not_found

 //-----------------------------------------------------------------------
 private static function helper__throw_bug_check__recursion_in_database_table_list
                                  ($problem_table_id)
 {
  $msg=sprintf
         (CODE_COMMON__ERROR_MESSAGES::C_BUG_CHECK__RECURSION_IN_DATABASE_TABLE_LIST__1,
          $problem_table_id);

  throw new Exception($msg);
 }//helper__throw_bug_check__recursion_in_database_table_list

 //-----------------------------------------------------------------------
 private static function helper__throw_bug_check__unexpected_parent_table_id
                                  ($table_id,
                                   $parent_table_id)
 {
  $msg=sprintf
         (CODE_COMMON__ERROR_MESSAGES::C_BUG_CHECK__UNEXPECTED_PARENT_TABLE_ID__2,
          $table_id,
          $parent_table_id);

  throw new Exception($msg);
 }//helper__throw_bug_check__unexpected_parent_table_id

 //-----------------------------------------------------------------------
 private static function helper__throw_bug_check__database_class_not_link_to_table
                                  ($class_id)
 {
  $msg=sprintf
         (CODE_COMMON__ERROR_MESSAGES::C_BUG_CHECK__DATABASE_CLASS_NOT_LINK_TO_TABLE__1,
          $class_id);

  throw new Exception($msg);
 }//helper__throw_bug_check__database_class_not_link_to_table

 //-----------------------------------------------------------------------
 private static function helper__throw_bug_check__database_table_not_found
                                  ($table_id)
 {
  $msg=sprintf
         (CODE_COMMON__ERROR_MESSAGES::C_BUG_CHECK__DATABASE_TABLE_NOT_FOUND__1,
          $table_id);

  throw new Exception($msg);
 }//helper__throw_bug_check__database_table_not_found

 //private data ----------------------------------------------------------
 private $m_tables_by_id;
 private $m_classes_by_id;
 private $m_classes_by_name;
};//class CODE_COMMON__DBCLASS_MANAGER

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\code\common
?>
