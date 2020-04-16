<?php

namespace am\lib\dbms\pgsql{
///////////////////////////////////////////////////////////////////////////////////////////////////
include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.error_messages.php');
///////////////////////////////////////////////////////////////////////////////////////////////////
use Exception;
///////////////////////////////////////////////////////////////////////////////////////////////////
//class PGSQL_TRANSACTION

class PGSQL_TRANSACTION
{
 private function __construct()
 {
  $this->m_connection=null;
 }//__construct

 //-----------------------------------------------------------------------
 public function __destruct()
 {
  if($this->m_connection)
  {
   try
   {
    $this->rollback(); //throw?
   }
   catch(Exception $e)
   {
    //note: ignore rollback errors
   }//catch
  }//if
 }//__destruct

 //-----------------------------------------------------------------------
 public static function create($connection)
 {
  $transaction=new self(); //throw

  $connection->internal__begin_transaction(); //throw

  $transaction->m_connection=$connection;

  return $transaction;
 }//create

 //-----------------------------------------------------------------------
 public function commit()
 {
  if(!$this->m_connection)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_TR_ERR__FAILED_TO_COMMIT__ALREADY_COMPLETED__0);
  }//if

  $this->m_connection->internal__commit_transaction(); //throw

  $this->m_connection=null;
 }//commit

 //-----------------------------------------------------------------------
 public function rollback()
 {
  if(!$this->m_connection)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_TR_ERR__FAILED_TO_ROLLBACK__ALREADY_COMPLETED__0);
  }//if

  $this->m_connection->internal__rollback_transaction(); //throw

  $this->m_connection=null;
 }//rollback

 //-----------------------------------------------------------------------
 public function build_dbobject_name(... $name_parts)
 {
  if(!$this->m_connection)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_TR_ERR__TRANSACTION_ALREADY_COMPLETED__0);
  }//if
  
  return $this->m_connection->build_dbobject_name(... $name_parts);
 }//build_dbobject_name

 //-----------------------------------------------------------------------
 public function query($sql,...$params)
 {
  if(!$this->m_connection)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_TR_ERR__CANT_EXECUTE_QUERY__ALREADY_COMPLETED__0);
  }//if

  $r=$this->m_connection->internal__query($sql,$params);

  if($r===FALSE)
  {
   $this->m_connection->internal__throw_last_error(PGSQL_ERROR_MESSAGES::C_CMD_ERR__FAILED_TO_EXECUTE_QUERY__0);
  }//if

  return $r;
 }//query

 //-----------------------------------------------------------------------
 //$gen_id
 //  - имя генератора. мы будем использовать это имя как есть.
 public function gen_id($seq_name)
 {
  $result_value=null;

  try
  {
   $r=$this->query('SELECT NEXTVAL($1);',$seq_name);

   if(!($row=$r->fetch_assoc()))
   {
    //ERROR - no records in resultset
    throw new Exception(PGSQL_ERROR_MESSAGES::C_BUG_CHECK__RESULT_SET_NOT_CONTAINS_RECORDS__0);
   }//if

   $result_value=$row['nextval'];

   if($row=$r->fetch_assoc())
   {
    //ERROR - multiple records in resultset
    throw new Exception(PGSQL_ERROR_MESSAGES::C_BUG_CHECK__RESULT_SET_CONTAINS_MULTIPLE_RECORDS__0);
   }//if
  }
  catch(Exception $e)
  {
   $msg=sprintf
         (PGSQL_ERROR_MESSAGES::C_GENID_ERR__FAILED_TO_GENERATE_ID__1,
          $seq_name);

   throw new Exception($msg,-1,$e);
  }//catch

  return $result_value;
 }//gen_id

 //-----------------------------------------------------------------------
 private $m_connection;
};//class PGSQL_TRANSACTION

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\lib\dbms\pgsql
?>
