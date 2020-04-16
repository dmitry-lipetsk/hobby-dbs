<?php

namespace am\lib\dbms\pgsql{
///////////////////////////////////////////////////////////////////////////////////////////////////
include_once('lib/php/dbms/pgsql/lib.dbms.pgsql.error_messages.php');
///////////////////////////////////////////////////////////////////////////////////////////////////
use Exception;
///////////////////////////////////////////////////////////////////////////////////////////////////
//class PGSQL_RESULT

class PGSQL_RESULT
{
 private function __construct()
 {
  $this->m_dbresource=null;
 }//__construct

 //-----------------------------------------------------------------------
 public function __destruct()
 {
  if(!$this->m_dbresource)
   return;

  $r=pg_free_result($this->m_dbresource);

  if($r===FALSE)
   throw new Exception(PGSQL_ERROR_MESSAGES::C_BUG_CHECK__FAILED_TO_FREE_RESULT__0); //ACHTUNG!
 }//__destruct

 //-----------------------------------------------------------------------
 public static function internal__create()
 {
  return new self();
 }//create

 //-----------------------------------------------------------------------
 public function internal__set_resource($dbresource)
 {
  $this->m_dbresource=$dbresource;
 }//internal__set_resource

 //interface -------------------------------------------------------------
 public function fetch_assoc()
 {
  $r=pg_fetch_assoc($this->m_dbresource);

  //! \todo
  //!  Check error

  return $r;
 }//fetch_assoc

 //private data ----------------------------------------------------------
 private $m_dbresource;
};//class PGSQL_RESULT

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\lib\dbms\pgsql
?>
