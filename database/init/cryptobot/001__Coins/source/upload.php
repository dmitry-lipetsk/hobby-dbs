<?php
ini_set("memory_limit", "512M");

///////////////////////////////////////////////////////////////////////////////////////////////////
include_once('common/common_init_code.php');
include_once('common/php/code.common.dbclass_manager.php');
include_once('common/php/code.common.dbobject_id.php');
include_once('database/php/data/common/code.database.data.common.class_names.php');
include_once('database/php/data/cryptobot/code.database.data.cryptobot.class_names.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
use am\code\common\CODE_COMMON__DBCLASS_MANAGER;
use am\code\common\CODE_COMMON__DBOBJECT_ID;
use am\code\database\data\common\CODE_DATABASE_DATA__COMMON__CLASS_NAMES;
use am\code\database\data\cryptobot\CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES;

///////////////////////////////////////////////////////////////////////////////////////////////////
//class LOADER_CFG

class LOADER_CFG
{
 const c_ROBOT_NAME ='database.init.cryptobot.001__Coins';

 const c_CSV_delimiter =';';
 const c_CSV_enclosure ='\'';

 const c_COL_NAME__name        ='name';
 const c_COL_NAME__short_name  ='short_name';
 const c_COL_NAME__url_logo    ='url_logo';
 const c_COL_NAME__volume      ='volume';
 const c_COL_NAME__algoritm    ='algoritm';
 const c_COL_NAME__generation  ='generation';
 const c_COL_NAME__twitter_id  ='twitter id';
 const c_COL_NAME__site_url    ='site_url';
 const c_COL_NAME__date        ='date';

 public static $sm_IgnoredCoin_Names
  =array('MonaCoin', 'HempCoin','Enigma');

 public static $sm_IgnoredCoin_ShortNames
  =array('CAT','BTM','SMART','NET','ACC',
         'ICN','BTG','GTC','CMT','ARC',
         'RMC','GCC','KNC','PUT','FAIR',
         'LBTC','CAN','CMS','MAG','BLT',
         'PRS','XIN','XID','BTCS','RCN',
         'RUPX','QBT','ETT','WIC',
         'HNC','CASH','BET','ENT');

 //-----------------------------------------------------------------------
 public static function TestCoinName__IsIgnored($name)
 {
  return array_search($name,self::$sm_IgnoredCoin_Names)!==false;
 }//TestCoinName__IsIgnored

 //-----------------------------------------------------------------------
 public static function TestCoinShortName__IsIgnored($short_name)
 {
  return array_search($short_name,self::$sm_IgnoredCoin_ShortNames)!==false;
 }//TestCoinShortName__IsIgnored
};//class LOADER_CFG

///////////////////////////////////////////////////////////////////////////////////////////////////
//class FILE

class FILE
{
 public function __construct($name)
 {
  $this->m_handle = fopen($name, 'r');

  if($this->m_handle===false)
   throw new Exception('Failed to open file ['.$name.']');
 }

 public function __destruct()
 {
  if($this->m_handle!==false)
  {
   $f=$this->m_handle;

   $this->m_handle=false;

   fclose($f);
  }//if
 }//__destruct

 public function get_handle()
 {
  return $this->m_handle;
 }//get_handle

 private $m_handle;
};//class FILE

///////////////////////////////////////////////////////////////////////////////////////////////////
//class LOADER_CTX

class LOADER_CTX
{
 public $m_COL_IDX__name;
 public $m_COL_IDX__short_name;
 public $m_COL_IDX__url_logo;
 public $m_COL_IDX__volume;
 public $m_COL_IDX__algoritm;
 public $m_COL_IDX__generation;
 public $m_COL_IDX__twitter_id;
 public $m_COL_IDX__site_url;
 public $m_COL_IDX__date;

 public $m_coins__by_name;
 public $m_coins__by_short_name;

 public function __construct()
 {
  $this->m_COL_IDX__name        =-1;
  $this->m_COL_IDX__short_name  =-1;
  $this->m_COL_IDX__url_logo    =-1;
  $this->m_COL_IDX__volume      =-1;
  $this->m_COL_IDX__algoritm    =-1;
  $this->m_COL_IDX__generation  =-1;
  $this->m_COL_IDX__twitter_id  =-1;
  $this->m_COL_IDX__site_url    =-1;
  $this->m_COL_IDX__date        =-1;

  //---------------
  $this->m_coins__by_name=array();
  $this->m_coins__by_short_name=array();

  //---------------
  $this->m_cn=COMMON_INIT_CODE::PGSQL_CN__create(); //throw

  $this->m_tr=COMMON_INIT_CODE::PGSQL_CN__begin_transaction($this->m_cn); //throw

  $this->m_class_mng=new CODE_COMMON__DBCLASS_MANAGER($this->m_tr);

  //Really OMG :)
  $this->m_robot_id=LOADER::get_robot_id($this, LOADER_CFG::c_ROBOT_NAME);
 }//__construct

 //--------------------------------------------------------------------------------
 public function Commit()
 {
  COMMON_INIT_CODE::PGSQL_TR__commit($this->m_tr); //throw
 }//Commit

 //--------------------------------------------------------------------------------
 public function GenID($seq_name)
 {
  return $this->m_tr->gen_id($seq_name);
 }//GenID

 //--------------------------------------------------------------------------------
 public function ExecuteQuery($sql,...$params)
 {
  return $this->m_tr->query($sql,...$params);
 }//ExecuteQuery

 //--------------------------------------------------------------------------------
 public function GetClassMng()
 {
  return $this->m_class_mng;
 }//GetClassMng

 //--------------------------------------------------------------------------------
 public function GetRobotID()
 {
  return $this->m_robot_id;
 }//GetRobotID

 //--------------------------------------------------------------------------------
 private $m_cn;
 private $m_tr;
 private $m_class_mng;
 private $m_robot_id;
};//class LOADER_CTX

///////////////////////////////////////////////////////////////////////////////////////////////////
//class COIN_DATA

class COIN_DATA
{
 public $m_name;
 public $m_short_name;
 public $m_url_logo;
 public $m_volume;
 public $m_algoritm;
 public $m_generation;
 public $m_twitter_id;
 public $m_site_url;
 public $m_date;

 //-----------------------------------------------------------------------
 public function __construct($ctx,&$row)
 {
  $this->m_name        =self::helper__get_value_not_empty($row,$ctx->m_COL_IDX__name      , LOADER_CFG::c_COL_NAME__name      );

  try
  {
   $this->m_short_name  =self::helper__get_value_not_empty($row,$ctx->m_COL_IDX__short_name, LOADER_CFG::c_COL_NAME__short_name);
   $this->m_url_logo    =self::helper__get_value          ($row,$ctx->m_COL_IDX__url_logo  , LOADER_CFG::c_COL_NAME__url_logo  );
   $this->m_volume      =self::helper__get_value          ($row,$ctx->m_COL_IDX__volume    , LOADER_CFG::c_COL_NAME__volume    );
   $this->m_algoritm    =self::helper__get_value          ($row,$ctx->m_COL_IDX__algoritm  , LOADER_CFG::c_COL_NAME__algoritm  );
   $this->m_generation  =self::helper__get_value          ($row,$ctx->m_COL_IDX__generation, LOADER_CFG::c_COL_NAME__generation);
   $this->m_twitter_id  =self::helper__get_value          ($row,$ctx->m_COL_IDX__twitter_id, LOADER_CFG::c_COL_NAME__twitter_id);
   $this->m_site_url    =self::helper__get_value          ($row,$ctx->m_COL_IDX__site_url  , LOADER_CFG::c_COL_NAME__site_url  );
   $this->m_date        =self::helper__get_value          ($row,$ctx->m_COL_IDX__date      , LOADER_CFG::c_COL_NAME__date      );
  }
  catch(Exception $e)
  {
   throw new Exception('failed to process coin ['.$this->m_name.'] ',-1,$e);
  }
 }//__construct

 //helper methods --------------------------------------------------------
 private static function helper__get_value(&$row,$idx,$header)
 {
  $v=$row[$idx];

  if($v===null)
   return null;

  $v=trim($v);

  if($v==='')
   return null;

  return $v;
 }//helper__get_value_not_empty

 //-----------------------------------------------------------------------
 private static function helper__get_value_not_empty(&$row,$idx,$header)
 {
  $v=$row[$idx];

  if($v===null || $v==='')
   throw new Exception('empty field ['.$header.']');

  $v=trim($v);

  if($v==='')
   throw new Exception('empty field ['.$header.']');

  return $v;
 }//helper__get_value_not_empty
};//class COIN_DATA

///////////////////////////////////////////////////////////////////////////////////////////////////
//class LOADER

class LOADER
{
 public static function load($ctx, $file_name)
 {
  COMMON_INIT_CODE::log_line('open file ['.$file_name.'] ...');

  $f=new FILE($file_name);

  COMMON_INIT_CODE::log_line('read header ...');

  $cRows=0;

  if(!($row_headers=self::helper__read_csv_line($f)))
   throw new Exception('Failed to read line with headers');

  ++$cRows;

  $ctx->m_COL_IDX__name        =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__name);
  $ctx->m_COL_IDX__short_name  =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__short_name);
  $ctx->m_COL_IDX__url_logo    =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__url_logo);
  $ctx->m_COL_IDX__volume      =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__volume);
  $ctx->m_COL_IDX__algoritm    =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__algoritm);
  $ctx->m_COL_IDX__generation  =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__generation);
  $ctx->m_COL_IDX__twitter_id  =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__twitter_id);
  $ctx->m_COL_IDX__site_url    =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__site_url);
  $ctx->m_COL_IDX__date        =self::helper__get_header_idx($row_headers,LOADER_CFG::c_COL_NAME__date);

  COMMON_INIT_CODE::log_line('start read lines ...');

  $coins=array();

  while($row=self::helper__read_csv_line($f))
  {
   ++$cRows;

   if(count($row)!=count($row_headers))
   {
    throw new Exception('Wrong row ['.$cRows.'] size: '.count($row).'. Expected size: '.count($row_headers));
   }

   try
   {
    $coin=new COIN_DATA($ctx,$row); //throw
   }
   catch(Exception  $e)
   {
    throw new Exception('Failed to process coin at row ['.$cRows.'] ',-1,$e);
   }//catch

   try
   {
    self::helper__reg_coin($ctx,$coin);
   }
   catch(Exception $e)
   {
    throw new Exception('Failed to process coin ['.$coin->m_name.'] at row ['.$cRows.'] ',-1,$e);
   }//catch
  }//while $row

  COMMON_INIT_CODE::log_line('stop read lines. processed '.$cRows.' row(s).');

  unset($f);

  COMMON_INIT_CODE::log_line('');
  COMMON_INIT_CODE::log_line('Start upload process ...');

  foreach($ctx->m_coins_by_name as $coin)
  {
   try
   {
    self::helper__uload_coin($ctx,$coin);
   }
   catch(Exception $e)
   {
    throw new Exception('Failed to uload coin ['.$coin->m_name.'].',-1,$e);
   }//catch
  }
 }//load

 //-----------------------------------------------------------------------
 //return
 // - object of CODE_COMMON__DBOBJECT_ID class
 public static function get_robot_id($ctx,$robot_name)
 {
  if(!$robot_name)
   throw new Exception('Empty robot name!');

  $robot_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__COMMON__CLASS_NAMES::COMMON_ROBOT);

  $sql='select c.OBJECT_ID from '.$robot_class_descr->get_table_descr()->get_table_name().' c '
      .'where c.OBJECT_CLASS=$1 and c.NAME=$2';

  $r=$ctx->ExecuteQuery($sql,$robot_class_descr->get_class_id(),$robot_name);

  if($row=$r->fetch_assoc())
  {
   $result=new CODE_COMMON__DBOBJECT_ID($row['object_id'],$robot_class_descr->get_class_id());

   if($r->fetch_assoc())
    throw new Exception('Find multiple records of robot ['.$robot_name.']');

   return $result;
  }//if

  //--------------------------------
  COMMON_INIT_CODE::log_line('registration of new robot ...');

  //create new robot record
  $new_coin_id=$ctx->GenID($robot_class_descr->get_seq_name());

  $result=new CODE_COMMON__DBOBJECT_ID($new_coin_id,$robot_class_descr->get_class_id());

  //-------- insert record to CORE.OBJECTS
  self::helper__insert_into_core_objects
   ($ctx,
    $robot_class_descr->get_table_descr()->get_parent(),
    $result->GetObjectId(),
    $result->GetObjectClass(),
    0,
    0);

  //-------- insert record to COMMON.ROBOTS
  $sql='insert into '.$robot_class_descr->get_table_descr()->get_table_name().PHP_EOL
      .'(OBJECT_ID,'.PHP_EOL
       .'OBJECT_CLASS,'.PHP_EOL
       .'NAME)'.PHP_EOL
      .'values($1,$2,$3)';

  $ctx->ExecuteQuery($sql,$result->GetObjectId(),$result->GetObjectClass(),$robot_name);

  //-------
  return $result;
 }//helper__get_robot_id

 //-----------------------------------------------------------------------
 private static function helper__reg_coin($ctx,$coin)
 {
  COMMON_INIT_CODE::log_line('reg coin '.$coin->m_name.' ...');

  if(LOADER_CFG::TestCoinName__IsIgnored($coin->m_name))
  {
   COMMON_INIT_CODE::log_line('ignored name');
   return;
  }//if

  if(LOADER_CFG::TestCoinShortName__IsIgnored($coin->m_short_name))
  {
   COMMON_INIT_CODE::log_line('ignored short name');
   return;
  }//if

  //--------
  $coin_name=strtoupper($coin->m_name);

  if(array_key_exists($coin_name,$ctx->m_coins_by_name))
   throw new Exception('Multiple definition of coin with name ['.$coin_name.'].');

  $ctx->m_coins_by_name[$coin_name]=$coin;

  //--------
  $coin_short_name=strtoupper($coin->m_short_name);

  if(array_key_exists($coin_short_name,$ctx->m_coins_by_short_name))
   throw new Exception('Multiple definition of coin with short name ['.$coin_short_name.'].');

  $ctx->m_coins_by_short_name[$coin_short_name]=$coin;
 }//helper__reg_coin

 //-----------------------------------------------------------------------
 private static function helper__uload_coin
                                 ($ctx,
                                  $coin)
 {
  COMMON_INIT_CODE::log_line('uload coin '.$coin->m_name.' ...');

  //1. search by short name
  //2.  - if found: check name and born_date (if not null)
  //    - if not found: insert new coin

  $coin_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_COIN);

  $sql
   ='select c.OBJECT_ID,c.OBJECT_CLASS,c.NAME,c.SHORT_NAME,c.CREATION_DATE'.PHP_EOL
   .'from '.$coin_class_descr->get_table_descr()->get_table_name().' c'.PHP_EOL
   .'where c.OBJECT_CLASS=$1 and c.SHORT_NAME=$2';

  $r=$ctx->ExecuteQuery
           ($sql,
            $coin_class_descr->get_class_id(),
            strtoupper($coin->m_short_name));

  if(!($row=$r->fetch_assoc()))
  {
   self::helper__insert_coin($ctx,$coin);
  }
  else
  {
   self::helper__update_coin($ctx,$coin,$row);
  }//else
 }//helper__uload_coin

 //-----------------------------------------------------------------------
 private static function helper__insert_coin($ctx,
                                             $coin)
 {
  COMMON_INIT_CODE::log_line('insert new coin');

  $coin_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_COIN);

  $new_coin_id=$ctx->GenID($coin_class_descr->get_seq_name());

  $result=new CODE_COMMON__DBOBJECT_ID($new_coin_id,$coin_class_descr->get_class_id());

  //-------- insert record to CORE.OBJECTS
  self::helper__insert_into_core_objects
   ($ctx,
    $coin_class_descr->get_table_descr()->get_parent(),
    $result->GetObjectId(),
    $result->GetObjectClass(),
    /*owner_id*/0,
    /*owner_class*/0);

  //-------- insert record to CRYPTOBOT.COINS
  $sql='insert into '.$coin_class_descr->get_table_descr()->get_table_name().PHP_EOL
      .'(OBJECT_ID,'.PHP_EOL
       .'OBJECT_CLASS,'.PHP_EOL
       .'NAME,'.PHP_EOL
       .'SHORT_NAME,'.PHP_EOL
       .'CREATION_DATE)'.PHP_EOL
      .'values($1,$2,$3,$4,$5)';

  $ctx->ExecuteQuery
        ($sql,
         $result->GetObjectId(),
         $result->GetObjectClass(),
         $coin->m_name,
         strtoupper($coin->m_short_name),
         $coin->m_date);
 }//helper__insert_coin

 //-----------------------------------------------------------------------
 private static function helper__update_coin($ctx,
                                             $coin,
                                             &$row)
 {
  COMMON_INIT_CODE::log_line('update exists coin');

  if($row['name']!==$coin->m_name)
  {
   throw new Exception('Detect different name of coin. Current: ['.$row['name'].']. New: ['.$coin->m_name.'].');
  }

  $columns_for_update=array();

  $row__creation_date=self::helper__get_array_element($row,'creation_date');

  if($row__creation_date===null)
  {
   //текущее описание монетки не имеет даты создания
   if($coin->m_date!==null)
   {
    COMMON_INIT_CODE::log_line('  need update creation date');

    $columns_for_update['creation_date']=$coin->m_date;
   }
  }
  else
  if($coin->m_date!==null)
  {
   $pg_date=$ctx->ExecuteQuery('select cast($1 as timestamp) as c',$coin->m_date)->fetch_assoc()['c'];

   if($row__creation_date!==$pg_date)
   {
    throw new Exception('different creation date of coin. Current: '.$row__creation_date.'. New: '.$pg_date.'.');
   }//if
  }//if

  if(count($columns_for_update)!=0)
  {
   $coin_class_descr
    =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_COIN);

   //подготовка запроса к обновлению монетки
   $params=array();

   $update_sql
    ='update '.$coin_class_descr->get_table_descr()->get_table_name().PHP_EOL
    .'set ';

   $n=0;

   foreach(array_keys($columns_for_update) as $c)
   {
    ++$n;

    if($n>1)
     $update_sql.=',';

    $update_sql.=$c.'=$'.$n;

    $params[]=$columns_for_update[$c];
   }//foreach

   $update_sql.=' where OBJECT_ID=$'.(++$n).' and OBJECT_CLASS=$'.(++$n);

   $params[]=$row['object_id'];
   $params[]=$row['object_class'];

   //COMMON_INIT_CODE::log_line('update sql: '.$update_sql);

   $ctx->ExecuteQuery($update_sql,...$params);
  }//if

 }//helper__update_coin

 //-----------------------------------------------------------------------
 private static function helper__insert_into_core_objects
                                 ($ctx,
                                  $table_descr,
                                  $object_id,
                                  $object_class,
                                  $owner_id,
                                  $owner_class)
 {
  $sql='insert into '.$table_descr->get_table_name().PHP_EOL
      .'(OBJECT_ID,'.PHP_EOL
       .'OBJECT_CLASS,'.PHP_EOL
       .'OWNER_ID,'.PHP_EOL
       .'OWNER_CLASS,'.PHP_EOL
       .'CREATOR_ID,'.PHP_EOL
       .'CREATOR_CLASS)'.PHP_EOL
      .'values($1,$2,$3,$4,$5,$6)';

  $creator_id=null;
  $creator_class=null;

  if($ctx->GetRobotID())
  {
   $creator_id    =$ctx->GetRobotID()->GetObjectId();
   $creator_class =$ctx->GetRobotID()->GetObjectClass();
  }

  $ctx->ExecuteQuery
   ($sql,
    $object_id,
    $object_class,
    $owner_id,
    $owner_class,
    $creator_id,
    $creator_class);
 }//helper__insert_into_core_objects

 //-----------------------------------------------------------------------
 private static function helper__read_csv_line($file)
 {
  return fgetcsv($file->get_handle(),0,LOADER_CFG::c_CSV_delimiter,LOADER_CFG::c_CSV_enclosure);
 }//helper__read_csv_line

 //-----------------------------------------------------------------------
 private static function helper__get_header_idx(&$headers,$name)
 {
  $r=array_keys($headers,$name);

  if(count($r)==0)
   throw new Exception('header ['.$name.'] not found.');

   if(count($r)>1)
   throw new Exception('multiple definition of header ['.$name.'].');

  COMMON_INIT_CODE::log_line('header ['.$name.'] index: '.$r[0]);

  return $r[0];
 }//helper__get_header_idx

 //-----------------------------------------------------------------------
 private static function &helper__get_array_element(&$arr,$key)
 {
  if(!array_key_exists($key,$arr))
   throw new Exception('Key ['.$key.'] not found!');

  return $arr[$key];
 }//helper__get_array_element
};//class LOADER

///////////////////////////////////////////////////////////////////////////////////////////////////

try
{
 COMMON_INIT_CODE::log_line('HELLO FROM ME!');

 //----------
 $ctx=new LOADER_CTX();

 //----------
 LOADER::load($ctx,'data\coins.csv');

 //----------
 $ctx->Commit();
}
catch(Exception $e)
{
 COMMON_INIT_CODE::print_error_exception($e);

 exit(1);
}//catch

exit(0);
?>