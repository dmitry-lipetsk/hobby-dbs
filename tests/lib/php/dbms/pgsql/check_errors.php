<?php

///////////////////////////////////////////////////////////////////////////////////////////////////
//class CHECK_ERRORS

class CHECK_ERRORS
{
 public static function check_exc__COMMON_ERROR__EMPTY_DATABASE_OBJECT_NAME($exc)
 {
  self::helper__check_exc
   ($exc,
    'Database object name is empty.');
 }//check_exc__COMMON_ERROR__EMPTY_DATABASE_OBJECT_NAME

 //-----------------------------------------------------------------------
 public static function check_exc__COMMON_ERROR__WRONG_DATABASE_OBJECT_NAME_STRUCTURE($exc)
 {
  self::helper__check_exc
   ($exc,
    'Incorrect structure of database object name.');
 }//check_exc__COMMON_ERROR__WRONG_DATABASE_OBJECT_NAME_STRUCTURE

 //-----------------------------------------------------------------------
 public static function check_exc__CN_ERR__FAILED_TO_CONNECT($exc)
 {
  self::helper__check_exc
   ($exc,
    'Failed to connect to postgresql database.');
 }//check_exc__CN_ERR__FAILED_TO_CONNECT

 //-----------------------------------------------------------------------
 public static function check_exc__CN_ERR__TRANSACTION_ALREDY_STARTED($exc)
 {
  self::helper__check_exc
   ($exc,
    'Transaction already started.');
 }//check_exc__CN_ERR__TRANSACTION_ALREDY_STARTED

 //-----------------------------------------------------------------------
 public static function check_exc__TR_ERR__TRANSACTION_ALREADY_COMPLETED($exc)
 {
  self::helper__check_exc
   ($exc,
    'Transaction already completed.');
 }//check_exc__TR_ERR__TRANSACTION_ALREADY_COMPLETED

 //-----------------------------------------------------------------------
 public static function check_exc__TR_ERR__FAILED_TO_COMMIT__ALREADY_COMPLETED($exc)
 {
  self::helper__check_exc
   ($exc,
    'Failed to commit transaction. Transaction already completed.');
 }//check_exc__TR_ERR__FAILED_TO_COMMIT__ALREADY_COMPLETED

 //-----------------------------------------------------------------------
 public static function check_exc__TR_ERR__FAILED_TO_ROLLBACK__ALREADY_COMPLETED($exc)
 {
  self::helper__check_exc
   ($exc,
    'Failed to rollback transaction. Transaction already completed.');
 }//check_exc__TR_ERR__FAILED_TO_ROLLBACK__ALREADY_COMPLETED

 //-----------------------------------------------------------------------
 public static function check_exc__TR_ERR__CANT_EXECUTE_QUERY__ALREADY_COMPLETED($exc)
 {
  self::helper__check_exc
   ($exc,
    'Can\'t execute query. Transaction already completed.');
 }//check_exc__TR_ERR__CANT_EXECUTE_QUERY__ALREADY_COMPLETED

 //-----------------------------------------------------------------------
 public static function check_exc__CMD_ERR__FAILED_TO_EXECUTE_QUERY($exc)
 {
  self::helper__check_exc
   ($exc,
    'Failed to execute query.');
 }//check_exc__CMD_ERR__FAILED_TO_EXECUTE_QUERY

 //-----------------------------------------------------------------------
 public static function check_exc__GENID_ERR__FAILED_TO_GENERATE_ID($exc,$seq_name)
 {
  self::helper__check_exc
   ($exc,
    'Failed to generate identifier. Sequence name: ['.$seq_name.'].');
 }//check_exc__GENID_ERR__FAILED_TO_GENERATE_ID

 //-----------------------------------------------------------------------
 public static function check_exc__BUG_CHECK__CANT_COMMIT_TR__TR_NOT_EXISTS($exc)
 {
  self::helper__check_exc
   ($exc,
    '[BUG CHECK] Can\'t commit transaction. Transaction not exists.');
 }//check_exc__BUG_CHECK__CANT_COMMIT_TR__TR_NOT_EXISTS

 //-----------------------------------------------------------------------
 public static function check_exc__BUG_CHECK__CANT_ROLLBACK_TR__TR_NOT_EXISTS($exc)
 {
  self::helper__check_exc
   ($exc,
    '[BUG CHECK] Can\'t rollback transaction. Transaction not exists.');
 }//check_exc__BUG_CHECK__CANT_ROLLBACK_TR__TR_NOT_EXISTS

 //-----------------------------------------------------------------------
 private static function helper__check_exc($exc,$expected_msg)
 {
  $actual_msg=$exc->getMessage();

  if($actual_msg!=$expected_msg)
  {
   $err_msg ='Wrong exception message.'.PHP_EOL;
   $err_msg.='Actual message  : ['.$actual_msg.']'.PHP_EOL;
   $err_msg.='Expected message: ['.$expected_msg.']'.PHP_EOL;

   throw new Exception($err_msg);
  }//if
 }//helper__check_exc
};//class CHECK_ERRORS

///////////////////////////////////////////////////////////////////////////////////////////////////

?>
