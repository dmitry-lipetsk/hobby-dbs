<?php

namespace am\lib\dbms\pgsql{
///////////////////////////////////////////////////////////////////////////////////////////////////
//class PGSQL_ERROR_MESSAGES

class PGSQL_ERROR_MESSAGES
{
 const C_COMMON_ERROR__UNEXPECTED_ERROR_IN_CODE__0
  ='Unexpected error in code.';

 const C_COMMON_ERROR__EMPTY_DATABASE_OBJECT_NAME__0
  ='Database object name is empty.';

 const C_COMMON_ERROR__WRONG_DATABASE_OBJECT_NAME_STRUCTURE__0
  ='Incorrect structure of database object name.';

 const C_CN_ERR__FAILED_TO_CONNECT__0
  ='Failed to connect to postgresql database.';

 const C_CN_ERR__TRANSACTION_ALREDY_STARTED__0
  ='Transaction already started.';

 const C_TR_ERR__TRANSACTION_ALREADY_COMPLETED__0
  ='Transaction already completed.';

 const C_TR_ERR__FAILED_TO_BEGIN_TRANSACTION__0
  ='Failed to start transaction.';

 const C_TR_ERR__FAILED_TO_COMMIT__ALREADY_COMPLETED__0
  ='Failed to commit transaction. Transaction already completed.';

 const C_TR_ERR__FAILED_TO_COMMIT__0
  ='Failed to commit transaction.';

 const C_TR_ERR__FAILED_TO_ROLLBACK__ALREADY_COMPLETED__0
  ='Failed to rollback transaction. Transaction already completed.';

 const C_TR_ERR__FAILED_TO_ROLLBACK__0
  ='Failed to rollback transaction.';

 const C_TR_ERR__CANT_EXECUTE_QUERY__ALREADY_COMPLETED__0
  ='Can\'t execute query. Transaction already completed.';

 const C_CMD_ERR__FAILED_TO_EXECUTE_QUERY__0
  ='Failed to execute query.';

 const C_GENID_ERR__FAILED_TO_GENERATE_ID__1
  ='Failed to generate identifier. Sequence name: [%s].';
  
 //-----------------------------------------------------------------------
 const C_BUG_CHECK__CANT_COMMIT_TR__TR_NOT_EXISTS__0
  ='[BUG CHECK] Can\'t commit transaction. Transaction not exists.';

 const C_BUG_CHECK__CANT_ROLLBACK_TR__TR_NOT_EXISTS__0
  ='[BUG CHECK] Can\'t rollback transaction. Transaction not exists.';

 const C_BUG_CHECK__FAILED_TO_FREE_RESULT__0
  ='[BUG CHECK] Failed to free postgresql resource.';

 const C_BUG_CHECK__RESULT_SET_NOT_CONTAINS_RECORDS__0
  ='[BUG CHECK] Resultset does not contain records.';

 const C_BUG_CHECK__RESULT_SET_CONTAINS_MULTIPLE_RECORDS__0
  ='[BUG CHECK] Resultset contains multiple records.';
};//class PGSQL_ERROR_MESSAGES

///////////////////////////////////////////////////////////////////////////////////////////////////
}//namespace am\lib\dbms\pgsql
?>
