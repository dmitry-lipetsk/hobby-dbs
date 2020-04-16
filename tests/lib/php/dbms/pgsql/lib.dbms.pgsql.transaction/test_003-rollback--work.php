<?php

include_once('test_services.php');

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $r=TEST_SERVICES::PGSQL_TR__query($tr,'select id,seq_schema,seq_name from CORE.CLASSES where name=$1;','COMMON.EMPLOYER');

 if(!($row=TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r)))
  throw new Exception('CLASS NOT FOUND!');

 $empl__seq_name=$cn->build_dbobject_name($row['seq_schema'],$row['seq_name']);
 $empl__class_id=$row['id'];

 TEST_SERVICES::log_line('empl__class_id: '.$empl__class_id);
 TEST_SERVICES::log_line('empl__seq_name: '.$empl__seq_name);

 $r=TEST_SERVICES::PGSQL_TR__query($tr,'select NEXTVAL($1);',$empl__seq_name);

 if(!($row=TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r)))
  throw new Exception('NEXTVAL return empty recordset!');

 $empl__id=$row['nextval'];

 TEST_SERVICES::log_line('new employer id: ',$empl__id);

 unset($r);

 //----------------------
 TEST_SERVICES::PGSQL_TR__query
  ($tr,
   'insert into CORE.OBJECTS (OBJECT_ID,OBJECT_CLASS,OWNER_ID,OWNER_CLASS) VALUES ($1,$2,$3,$4)',
   $empl__id,
   $empl__class_id,
   0,
   0);

 //---------------------- verification that row was inserted
 TEST_SERVICES::log_line('----------------------------');

 $r=TEST_SERVICES::PGSQL_TR__query
  ($tr,
   'select OBJECT_ID,OBJECT_CLASS from core.objects where OBJECT_ID=$1 and OBJECT_CLASS=$2',
   $empl__id,
   $empl__class_id);

 if(!($row=TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r)))
  throw new Exception('ROW NOT FOUND!!!');

 TEST_SERVICES::log_line('Ok. Row was selected.');

 if($row['object_id']!==$empl__id)
  throw new Exception('Wrong object_id: '.$row['object_id']);

 if($row['object_class']!==$empl__class_id)
  throw new Exception('Wrong object_class: '.$row['object_class']);

 TEST_SERVICES::log_line('Delete test row');

 TEST_SERVICES::PGSQL_TR__query
  ($tr,
   'delete from core.objects where OBJECT_ID=$1 and OBJECT_CLASS=$2',
   $empl__id,
   $empl__class_id);

 TEST_SERVICES::PGSQL_TR__rollback($tr);

 //---------------------- verification that row was deleted
 TEST_SERVICES::log_line('----------------------------');

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $r=TEST_SERVICES::PGSQL_TR__query
  ($tr,
   'select OBJECT_ID,OBJECT_CLASS from core.objects where OBJECT_ID=$1 and OBJECT_CLASS=$2',
   $empl__id,
   $empl__class_id);

 if(TEST_SERVICES::PGSQL_RESULT__fetch_assoc($r))
  throw new Exception('ROW NOT DELETED!!!');

 TEST_SERVICES::log_line('Ok. Row was deleted.');

 TEST_SERVICES::PGSQL_TR__commit($tr);

 //----------------------
 unset($tr);
 unset($cn);
}
catch(Exception $e)
{
 TEST_SERVICES::print_error_exception($e);

 exit(1);
}//catch

exit(0);
?>
