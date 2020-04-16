<?php

namespace am\code\common{
///////////////////////////////////////////////////////////////////////////////////////////////////
//class CODE_COMMON__DBTABLE_DESCR

class CODE_COMMON__DBTABLE_DESCR
{
 public function __construct($table_id,
                             $table_name,
                             $table_parent)
 {
  $this->m_table_id     =$table_id;
  $this->m_table_name   =$table_name;
  $this->m_table_parent =$table_parent;
 }//__construct

 //interface -------------------------------------------------------------
 public function get_table_id()
 {
  return $this->m_table_id;
 }//get_table_id

 //-----------------------------------------------------------------------
 public function get_table_name()
 {
  return $this->m_table_name;
 }//get_table_name

 //-----------------------------------------------------------------------
 public function get_parent()
 {
  return $this->m_table_parent;
 }//get_parent

 //private data ----------------------------------------------------------
 private $m_table_id;
 private $m_table_name;
 private $m_table_parent;
};//class CODE_COMMON__DBTABLE_DESCR

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\code\common
