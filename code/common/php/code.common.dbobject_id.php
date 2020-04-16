<?php

namespace am\code\common{
///////////////////////////////////////////////////////////////////////////////////////////////////
//class CODE_COMMON__DBOBJECT_ID

class CODE_COMMON__DBOBJECT_ID
{
 public function __construct($obj_id,$obj_class)
 {
  //! \todo
  //!  Check arguments - should contain the numerics
  
  $this->m_obj_id    =$obj_id;
  $this->m_obj_class =$obj_class;
 }//CODE_COMMON__DBOBJECT_ID
 
 //interface -------------------------------------------------------------
 public function GetObjectId()
 {
  return $this->m_obj_id;
 }//GetObjectID

 //-----------------------------------------------------------------------
 public function GetObjectClass()
 {
  return $this->m_obj_class;
 }//GetObjectClass
 
 //private data ----------------------------------------------------------
 private $m_obj_id;
 private $m_obj_class;
};//class CODE_COMMON__DBOBJECT_ID

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\code\common
?>
