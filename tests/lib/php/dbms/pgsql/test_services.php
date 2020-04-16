<?php

include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.connection.php');
include_once('test_cfg.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\lib\dbms\pgsql\PGSQL_CONNECTION;

///////////////////////////////////////////////////////////////////////////////////////////////////
//class TEST_SERVICES

class TEST_SERVICES
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
 public static function throw_we_wait_error()
 {
  throw new Exception('We wait the exception!');
 }//throw_we_wait_error

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
 public static function print_exception_ok($exc)
 {
  self::log('OK. We catch the exception:'.PHP_EOL);

  if(!$exc)
  {
   self::log('[print_exception_ok] NO EXCEPTION!!!'.PHP_EOL);

   return;
  }

  $n=0;

  while($exc)
  {
   $n+=1;

   self::log($n.'. '.$exc->getMessage().PHP_EOL);

   $exc=$exc->getPrevious();
  }//while
 }//print_exception_ok

 //-----------------------------------------------------------------------
 public static function PGSQL_CN__create()
 {
  self::log('create PG connection ...'.PHP_EOL);

  $cn=PGSQL_CONNECTION::create
       (TEST_CFG::c_pg_server,
        TEST_CFG::c_pg_database,
        TEST_CFG::c_pg_user_id,
        TEST_CFG::c_pg_pswd);

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

 //-----------------------------------------------------------------------
 public static function PGSQL_TR__query($tr,$sql,...$params)
 {
  self::log('execute query ['.$sql.'] ...'.PHP_EOL);

  return $tr->query($sql,...$params);//throw
 }//PGSQL_TR__query

 //-----------------------------------------------------------------------
 public static function PGSQL_RESULT__fetch_assoc($result)
 {
  self::log('fetch assoc from PG result ...'.PHP_EOL);

  return $result->fetch_assoc();
 }//PGSQL_RESULT__fetch_assoc

 //-----------------------------------------------------------------------
 public static function PGSQL_TR__gen_id($tr,$seq_name)
 {
  self::log('generate identifier ['.$seq_name.'] ...'.PHP_EOL);

  return $tr->gen_id($seq_name);//throw
 }//PGSQL_TR__gen_id
};//class TEST_SERVICES

///////////////////////////////////////////////////////////////////////////////////////////////////

?>
