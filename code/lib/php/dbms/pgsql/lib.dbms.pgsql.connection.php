<?php

namespace am\lib\dbms\pgsql{
///////////////////////////////////////////////////////////////////////////////////////////////////
include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.transaction.php');
include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.result.php');
include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.error_messages.php');
///////////////////////////////////////////////////////////////////////////////////////////////////
use Exception;
///////////////////////////////////////////////////////////////////////////////////////////////////
//class PGSQL_CONNECTION

class PGSQL_CONNECTION
{
 private function __construct($connection_string)
 {
  $this->m_dbconn=null;
  $this->m_dblasterror=null;
  $this->m_has_transaction=false;

  //----------
  $cn=pg_connect($connection_string);

  //note: pg_last_error() not works here! Because no connection here.
  //$m_dblasterror=pg_last_error(); //throw

  if($cn===FALSE)
   $this->internal__throw_last_error(PGSQL_ERROR_MESSAGES::C_CN_ERR__FAILED_TO_CONNECT__0);

  $this->m_dbconn=$cn;
 }//__construct

 public function __destruct()
 {
  if($this->m_dbconn)
  {
   pg_close($this->m_dbconn);
  }//if
 }//__destruct

 //-----------------------------------------------------------------------
 public static function create_from_cn_str($connection_string)
 {
  return new self($connection_string); //throw
 }//create_from_cn_str

 //-----------------------------------------------------------------------
 public static function create($server,$database,$user,$password)
 {
  $connection_string
   =sprintf('host=%s dbname=%s user=%s password=%s',
            $server,
            $database,
            $user,
            $password);

  return self::create_from_cn_str($connection_string);
 }//create

 //-----------------------------------------------------------------------
 public function begin_transaction()
 {
  return PGSQL_TRANSACTION::create($this);
 }//begin_transaction

 //-----------------------------------------------------------------------
 public function build_dbobject_name(... $name_parts)
 {
  $r=null;

  $c=count($name_parts);

  if($c==0)
   throw new Exception(PGSQL_ERROR_MESSAGES::C_COMMON_ERROR__EMPTY_DATABASE_OBJECT_NAME__0);

  if(self::helper__str_is_null_or_empty($name_parts[$c-1]))
   throw new Exception(PGSQL_ERROR_MESSAGES::C_COMMON_ERROR__WRONG_DATABASE_OBJECT_NAME_STRUCTURE__0);

  foreach($name_parts as $n)
  {
   if(!self::helper__str_is_null_or_empty($n))
   {
    $n=self::helper__wrap_dbobject_name($n);
   }
   else
   if($r==null)
   {
    continue;
   }//if

   if($r!=null)
   {
    $r.='.';
   }//if

   $r.=$n;
  }//foreach

  return $r;
 }//build_dbobject_name

 //internal interface ----------------------------------------------------
 public function internal__begin_transaction()
 {
  if($this->m_has_transaction)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_CN_ERR__TRANSACTION_ALREDY_STARTED__0);
  }

  $r=$this->internal__query('BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE');

  if($r==FALSE)
  {
   //ERROR - failed to transaction start
   $this->internal__throw_last_error(PGSQL_ERROR_MESSAGES::C_TR_ERR__FAILED_TO_BEGIN_TRANSACTION__0);
  }//if

  $this->m_has_transaction=true;

  /*no return code*/
 }//internal__begin_transaction

 //-----------------------------------------------------------------------
 public function internal__commit_transaction()
 {
  if(!$this->m_has_transaction)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_BUG_CHECK__CANT_COMMIT_TR__TR_NOT_EXISTS__0);
  }

  $r=$this->internal__query('COMMIT');

  if($r==FALSE)
  {
   //ERROR - failed to transaction start
   $this->internal__throw_last_error(PGSQL_ERROR_MESSAGES::C_TR_ERR__FAILED_TO_COMMIT__0);
  }//if

  $this->m_has_transaction=false;

  /*no return code*/
 }//internal__commit_transaction

 //-----------------------------------------------------------------------
 public function internal__rollback_transaction()
 {
  if(!$this->m_has_transaction)
  {
   throw new Exception(PGSQL_ERROR_MESSAGES::C_BUG_CHECK__CANT_ROLLBACK_TR__TR_NOT_EXISTS__0);
  }

  $r=$this->internal__query('ROLLBACK');

  if($r==FALSE)
  {
   //ERROR - failed to transaction start
   $this->internal__throw_last_error(PGSQL_ERROR_MESSAGES::C_TR_ERR__FAILED_TO_ROLLBACK__0);
  }//if

  $this->m_has_transaction=false;

  /*no return code*/
 }//internal__rollback_transaction

 //-----------------------------------------------------------------------
 //$sql
 //$params - null or array with param values
 public function internal__query($sql,$params=null)
 {
  $rr=PGSQL_RESULT::internal__create();

  if($params!=null)
  {
   $r=pg_query_params($this->m_dbconn,$sql,$params); //throw
  }
  else
  {
   $r=pg_query($this->m_dbconn,$sql); //throw
  }

  $this->m_dblasterror=pg_last_error(); //throw [out of memory]

  if($r===FALSE)
   return $r;

  $rr->internal__set_resource($r);

  return $rr;
 }//internal__query

 //-----------------------------------------------------------------------
 public function internal__throw_last_error($user_msg)
 {
  $server_msg=$this->m_dblasterror;

  $server_exc=null;

  if($server_msg)
  {
   $server_exc=new Exception($server_msg);
  }//if

  $user_exc=null;

  if($user_msg)
  {
   $user_exc=new Exception($user_msg,-1,$server_exc);
  }
  else
  {
   $user_exc=$server_exc;
  }//else

  //----------
  if(!$user_exc)
  {
   $user_exc=new Exception(PGSQL_ERROR_MESSAGES::C_COMMON_ERROR__UNEXPECTED_ERROR_IN_CODE__0);
  }

  throw $user_exc;
 }//internal__throw_last_error

 //-----------------------------------------------------------------------
 private function helper__str_is_null_or_empty($s)
 {
  if($s==null)
   return true;

  if($s=='')
   return true;

  return false;
 }//helper__str_is_null_or_empty

 //-----------------------------------------------------------------------
 private function helper__wrap_dbobject_name($s)
 {
  if(strpos($s,'"')!==FALSE)
   $s=str_replace('"','""',$s);

  return '"'.$s.'"';
 }//helper__wrap_dbobject_name

 //private data ----------------------------------------------------------
 private $m_dbconn;
 private $m_dblasterror;
 private $m_has_transaction;
};//class PGSQL_CONNECTION

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\lib\dbms\pgsql
?>
