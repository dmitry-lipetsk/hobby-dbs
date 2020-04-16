<?php

namespace am\code\common{
///////////////////////////////////////////////////////////////////////////////////////////////////
//class CODE_COMMON__ERROR_MESSAGES

class CODE_COMMON__ERROR_MESSAGES
{
 const C_DBCLASS_MNG_ERR__OBJECT_NOT_INITED__0
  ='Object not initialized';

 //params: class id
 const C_DBCLASS_MNG_ERR__DBCLASS_ID_NOT_FOUND__1
  ='Database class with id [%s] not found.';

 //params: class name
 const C_DBCLASS_MNG_ERR__DBCLASS_NAME_NOT_FOUND__1
  ='Database class with name [%s] not found.';

 //params: table id, parent table id
 const C_BUG_CHECK__UNEXPECTED_PARENT_TABLE_ID__2
  ='[BUG CHECK] Bad data for database table [%s]. Unexpected parent table id [%s].';
 //params: table id, parent table id

 //params: problem table id
 const C_BUG_CHECK__RECURSION_IN_DATABASE_TABLE_LIST__1
  ='[BUG CHECK] Recursion in database table list. Problem table id: %s.';

 //params: class id
 const C_BUG_CHECK__DATABASE_CLASS_NOT_LINK_TO_TABLE__1
  ='[BUG CHECK] Database class [%s] not link to table.';

  //params: table id
 const C_BUG_CHECK__DATABASE_TABLE_NOT_FOUND__1
  ='[BUG CHECK] Database table [%s] not found.';
};//class CODE_COMMON__ERROR_MESSAGES

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\code\common
