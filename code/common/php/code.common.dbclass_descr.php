<?php

namespace am\code\common{
///////////////////////////////////////////////////////////////////////////////////////////////////
//class CODE_COMMON__DBCLASS_DESCR

class CODE_COMMON__DBCLASS_DESCR
{
 public function __construct($class_id,
                             $class_name,
                             $table_descr,
                             $seq_name)
 {
  $this->m_class_id    =$class_id;
  $this->m_class_name  =$class_name;
  $this->m_table_descr =$table_descr;
  $this->m_seq_name    =$seq_name;
 }//__construct

 //interface -------------------------------------------------------------
 public function get_class_id()
 {
  return $this->m_class_id;
 }//get_class_id

 //-----------------------------------------------------------------------
 public function get_class_name()
 {
  return $this->m_class_name;
 }//get_class_name

 //-----------------------------------------------------------------------
 public function get_table_descr()
 {
  return $this->m_table_descr;
 }//get_table_descr

 //-----------------------------------------------------------------------
 public function get_seq_name()
 {
  return $this->m_seq_name;
 }//get_seq_name

 //private data ----------------------------------------------------------
 private $m_class_id;
 private $m_class_name;
 private $m_table_descr;
 private $m_seq_name;
};//class CODE_COMMON__DBCLASS_DESCR

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\code\common
