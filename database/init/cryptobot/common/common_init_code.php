<?php

include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.connection.php');
include_once('init_cfg.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\lib\dbms\pgsql\PGSQL_CONNECTION;

///////////////////////////////////////////////////////////////////////////////////////////////////
//class COMMON_INIT_CODE

class COMMON_INIT_CODE
{
 public static function log(... $args)
 {
  if($args)
  {
   foreach($args as $a)
   {
    echo $a;
   }
  }//if
 }//log

 //-----------------------------------------------------------------------
 public static function log_line(... $args)
 {
  $args[]=PHP_EOL;

  self::log(...$args);
 }//log_line

 //-----------------------------------------------------------------------
 public static function print_error_exception($exc)
 {
  if(!$exc)
  {
   self::log('[print_error_exception] NO EXCEPTION!!!'.PHP_EOL);

   return;
  }

  $n=0;

  while($exc)
  {
   $n+=1;

   self::log('ERROR: '.$n.'. '.$exc->getMessage().PHP_EOL);

   $exc=$exc->getPrevious();
  }//while
 }//print_error_exception

 //-----------------------------------------------------------------------
 public static function PGSQL_CN__create()
 {
  self::log('create PG connection ...'.PHP_EOL);

  $cn=PGSQL_CONNECTION::create
       (INIT_CFG::c_pg_server,
        INIT_CFG::c_pg_database,
        INIT_CFG::c_pg_user_id,
        INIT_CFG::c_pg_pswd);

  return $cn;
 }//PGSQL_CN__create

 //-----------------------------------------------------------------------
 public static function PGSQL_CN__begin_transaction($cn)
 {
  self::log('start PG transaction ...'.PHP_EOL);

  return $cn->begin_transaction();
 }//PGSQL_CN__begin_transaction

 //-----------------------------------------------------------------------
 public static function PGSQL_TR__commit($tr)
 {
  self::log('commit PG transaction ...'.PHP_EOL);

  return $tr->commit();
 }//PGSQL_TR__commit

 //-----------------------------------------------------------------------
 public static function PGSQL_TR__rollback($tr)
 {
  self::log('rollback PG transaction ...'.PHP_EOL);

  return $tr->rollback();
 }//PGSQL_TR__rollback
};//class COMMON_INIT_CODE

 ///////////////////////////////////////////////////////////////////////////////////////////////////
?>
