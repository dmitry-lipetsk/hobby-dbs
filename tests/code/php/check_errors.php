<?php

///////////////////////////////////////////////////////////////////////////////////////////////////
//class CHECK_ERRORS

class CHECK_ERRORS
{
 public static function check_exc__DBCLASS_MNG_ERR__DBCLASS_ID_NOT_FOUND($exc,$class_id)
 {
  self::helper__check_exc
   ($exc,
    'Database class with id ['.$class_id.'] not found.');
 }//check_exc__DBCLASS_MNG_ERR__DBCLASS_ID_NOT_FOUND

 //-----------------------------------------------------------------------
 public static function check_exc__DBCLASS_MNG_ERR__DBCLASS_NAME_NOT_FOUND($exc,$class_name)
 {
  self::helper__check_exc
   ($exc,
    'Database class with name ['.$class_name.'] not found.');
 }//check_exc__DBCLASS_MNG_ERR__DBCLASS_NAME_NOT_FOUND

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
