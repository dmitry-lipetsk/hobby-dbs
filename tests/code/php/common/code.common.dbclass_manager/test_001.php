<?php

include_once('common/php/code.common.dbclass_manager.php');
include_once('database/php/data/core/code.database.data.core.table_names.php');
include_once('test_services.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\common\CODE_COMMON__DBCLASS_MANAGER;
use am\code\database\data\core\CODE_DATABASE_DATA__CORE__TABLE_NAMES;
///////////////////////////////////////////////////////////////////////////////////////////////////

function check_class_descr($c,$data_id,$data_name)
{
 if($c->get_class_id()!=$data_id)
  throw new Exception('Wrong class id ['.$c->get_class_id().']['.$data_id.']');

 if($c->get_class_name()!=$data_name)
  throw new Exception('Wrong class name ['.$c->get_class_name().']['.$data_name.']');
}//check_class_descr

try
{
 $cn=TEST_SERVICES::PGSQL_CN__create();
 $tr=TEST_SERVICES::PGSQL_CN__begin_transaction($cn);

 $mng=new CODE_COMMON__DBCLASS_MANAGER($tr);

 $sql='select ID,NAME FROM '.CODE_DATABASE_DATA__CORE__TABLE_NAMES::CORE_CLASSES;

 $r=TEST_SERVICES::PGSQL_TR__query($tr,$sql);

 while($row=$r->fetch_assoc())
 {
  $c1=$mng->get_class_by_id($row['id']);

  $c2=$mng->get_class_by_name($row['name']);

  if($c1!=$c2)
   throw new Exception('Problem with class ['.$row['id'].']['.$row['name'].']');

  check_class_descr
   ($c1,
    $row['id'],
    $row['name']);

  check_class_descr
   ($c2,
    $row['id'],
    $row['name']);

  //-------
  if(!$c1->get_table_descr())
   throw new Exception('NO TABLE!');
  
  $tables='';
  
  for($t=$c1->get_table_descr();$t;$t=$t->get_parent())
  {
   $tables.=$t->get_table_name().';';
  }
  
  //-------
  TEST_SERVICES::log_line('----------------------------------');
  TEST_SERVICES::log_line('class_id   :'.$c1->get_class_id());
  TEST_SERVICES::log_line('class_name :'.$c1->get_class_name());
  TEST_SERVICES::log_line('table_name :'.$tables);
  TEST_SERVICES::log_line('seq_name   :'.$c1->get_seq_name());
 }//while

 TEST_SERVICES::log_line();

 unset($r);

 TEST_SERVICES::PGSQL_TR__commit($tr);

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
