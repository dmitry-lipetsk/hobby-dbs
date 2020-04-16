<?php

include_once('test_services.php');

function helper__check_name($tr,$name_parts,$expected_result)
{
 TEST_SERVICES::log_line('--------------------------------');
 TEST_SERVICES::log_line('name parts: ['.implode(',',$name_parts).']');

 $name_parts__copy=$name_parts;
 
 $actual_result=$tr->build_dbobject_name(...$name_parts);

 if($actual_result!=$expected_result)
 {
  throw new Exception('wrong result: ['.$actual_result.']');
 }
 
 if($name_parts__copy!==$name_parts)
  throw new Exception('name_parts was changed!');
}//helper__check_name

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();

 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);
 
 helper__check_name
  ($tr,
   array('a'),
   '"a"');

 helper__check_name
  ($tr,
   array('"a'),
   '"""a"');

 helper__check_name
  ($tr,
   array('"a','b'),
   '"""a"."b"');

 helper__check_name
  ($tr,
   array(null,'b'),
   '"b"');

 helper__check_name
  ($tr,
   array('','b'),
   '"b"');

 helper__check_name
  ($tr,
   array('','  '),
   '"  "');

 helper__check_name
  ($tr,
   array('','"'),
   '""""');

 helper__check_name
  ($tr,
   array('',' a ','b','c'),
   '" a "."b"."c"');

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
