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
 const c_ROBOT_NAME ='database.init.cryptobot.002__CryptoCurrencyChart';

 const c_BTC    = 'BTC';
 const c_USD    = 'USD';
 const c_MARKET = 'CRYPTO_CURRENCY_CHART';
};//class LOADER_CFG

///////////////////////////////////////////////////////////////////////////////////////////////////
//class LOADER_CTX

class LOADER_CTX
{
 public function __construct()
 {
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
//class LOADER

class LOADER
{
 public static function load_by_usd($ctx,$file_name)
 {
  $usd_id=self::helper__get_currency_id($ctx,LOADER_CFG::c_USD);

  COMMON_INIT_CODE::log_line('USD id: ['.$usd_id->GetObjectId().':'.$usd_id->GetObjectClass().']');

  //----------
  $market_id=self::helper__get_market_id($ctx,LOADER_CFG::c_MARKET);

  COMMON_INIT_CODE::log_line('Market id: ['.$market_id->GetObjectId().':'.$market_id->GetObjectClass().']');

  //----------
  self::helper__exec
   ($ctx,
    $file_name,
    $usd_id,
    $market_id);
 }//load_by_usd

 //----------------------------------------------------------------
 public static function load_by_btc($ctx, $file_name)
 {
  $btc_id=self::helper__get_coin_id($ctx,LOADER_CFG::c_BTC);

  COMMON_INIT_CODE::log_line('BTC id: ['.$btc_id->GetObjectId().':'.$btc_id->GetObjectClass().']');

  //----------
  $market_id=self::helper__get_market_id($ctx,LOADER_CFG::c_MARKET);

  COMMON_INIT_CODE::log_line('Market id: ['.$market_id->GetObjectId().':'.$market_id->GetObjectClass().']');

  //----------
  self::helper__exec
   ($ctx,
    $file_name,
    $btc_id,
    $market_id);
 }//load_by_btc

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

 //----------------------------------------------------------------
 private static function helper__exec($ctx,$file_name,$currency_id,$market_id)
 {
  COMMON_INIT_CODE::log_line('load file ['.$file_name.'] ...');

  $json_raw=file_get_contents($file_name);

  COMMON_INIT_CODE::log_line('length: '.strlen($json_raw));

  COMMON_INIT_CODE::log_line('decode json ...');

  $json=json_decode($json_raw,/*assoc*/true);

  unset($json_raw);

  //COMMON_INIT_CODE::log_line('json structure');
  //self::helper__print_json_structure($json,'');

  //--------------------------------
  COMMON_INIT_CODE::log_line('check structure ...');

  $node__labels=self::helper__get_array_element($json,'labels');

  if(!is_array($node__labels))
   throw new Exception('labels node is not array!');

  $node__data=self::helper__get_array_element($json,'data');

  if(!is_array($node__data))
   throw new Exception('data node is not array!');

  foreach(array_keys($node__data) as &$node_coin)
  {
   $coin_data=&$node__data[$node_coin];

   if(!is_array($coin_data))
    throw new Exception('coin ['.$node_coin.'] contains wrong data: '.gettype($coin_data));

   if(count($coin_data)!=count($node__labels))
    throw new Exception('coin ['.$node_coin.'] contains incorrect elements: '.count($coin_data).' Expected: '.count($node__labels));
  }//foreach

  //--------------------------------
  $n=0;

  foreach(array_keys($node__data) as &$node_coin)
  {
   ++$n;

   $coin_data=&$node__data[$node_coin];

   COMMON_INIT_CODE::log_line('--------------------------------- '.$n.'. load coin ['.$node_coin.'] history ...');

   self::helper__load_coin_history
    ($ctx,
     strtoupper($node_coin),
     $node__labels,
     $coin_data,
     $currency_id,
     $market_id);
  }//foreach $node_coin
 }//helper__exec

 //-----------------------------------------------------------------------
 private static function helper__load_coin_history($ctx,
                                                   $coin_name,
                                                   &$labels,
                                                   &$history,
                                                   $currency_id,
                                                   $market_id)
 {
  $coin_id=self::helper__get_coin_id($ctx,$coin_name);

  COMMON_INIT_CODE::log_line('coin id    : ['.$coin_id->GetObjectId().':'.$coin_id->GetObjectClass().']');
  COMMON_INIT_CODE::log_line('currency id: ['.$currency_id->GetObjectId().':'.$currency_id->GetObjectClass().']');
  COMMON_INIT_CODE::log_line('market id  : ['.$market_id->GetObjectId().':'.$market_id->GetObjectClass().']');

  if(count($labels)!=count($history))
   throw new Exception('[BUG CHECK] coin ['.$node_coin.'] contains incorrect elements: '.count($history).' Expected: '.count($labels));

  $price_id=self::helper__get_price_id
            ($ctx,
             $market_id,
             $coin_id,
             $currency_id);

  COMMON_INIT_CODE::log_line('price id   : ['.$price_id->GetObjectId().':'.$price_id->GetObjectClass().']');

  $price_history_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_PRICE_HISTORY);

  //------
  $select_sql
    ='select PRICE_VALUE from '.$price_history_class_descr->get_table_descr()->get_table_name().PHP_EOL
    .'where OBJECT_CLASS=$1 and PRICE_ID=$2 and PRICE_CLASS=$3 and PRICE_DATE=$4';

  //------
  $insert_sql
    ='insert into '.$price_history_class_descr->get_table_descr()->get_table_name().PHP_EOL
     .'(OBJECT_ID,'.PHP_EOL
      .'OBJECT_CLASS,'.PHP_EOL
      .'PRICE_ID,'.PHP_EOL
      .'PRICE_CLASS,'.PHP_EOL
      .'PRICE_DATE,'.PHP_EOL
      .'PRICE_VALUE)'.PHP_EOL
    .'values($1,$2,$3,$4,$5,$6)';

  //------
  $n_inserted=0;
  $n_skeeped=0;

  for($i=0;$i!=count($labels);++$i)
  {
   $d=$labels[$i];
   $v=$history[$i];

   //------
   if(!$d)
    throw new Exception('[BUG CHECK] Bad date at index ['.$i.']');

   //------
   if(self::helper__skeep_price_value($v))
    continue;

   //------
   $r=$ctx->ExecuteQuery
       ($select_sql,
        $price_history_class_descr->get_class_id(),
        $price_id->GetObjectId(),
        $price_id->GetObjectClass(),
        $d);

   if($row=$r->fetch_assoc())
   {
    if($row['price_value']==$v)
    {
     ++$n_skeeped;

     continue;
    }//if

    $msg='Different value of price with date ['.$d.']. New value: '.$v.'. Old value: '.$row['price_value'];

    throw new Exception($msg);
   }//if

   //insert new record
   $r=$ctx->ExecuteQuery
       ($insert_sql,
        $ctx->GenID($price_history_class_descr->get_seq_name()),
        $price_history_class_descr->get_class_id(),
        $price_id->GetObjectId(),
        $price_id->GetObjectClass(),
        $d,
        $v);

   ++$n_inserted;
  }//for $i

  COMMON_INIT_CODE::log_line('');
  COMMON_INIT_CODE::log_line('inserted: '.$n_inserted);
  COMMON_INIT_CODE::log_line('skeeped : '.$n_skeeped);
 }//helper__load_coin_history

 //-----------------------------------------------------------------------
 private static function helper__skeep_price_value($v)
 {
  if($v===null)
   return true;

  if($v==='')
   return true;

  if(strtoupper($v)==='NULL')
   return true;

  return false;
 }//helper__skeep_price_value

 //-----------------------------------------------------------------------
 //return
 // - object of CODE_COMMON__DBOBJECT_ID class
 private static function helper__get_price_id($ctx,$market_id,$asset_id,$currency_id)
 {
  $price_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_PRICE);

  $sql='select c.OBJECT_ID from '.$price_class_descr->get_table_descr()->get_table_name().' c '.PHP_EOL
      .'where c.MARKET_ID=$1 and'.PHP_EOL
            .'c.MARKET_CLASS=$2 and'.PHP_EOL
            .'c.ASSET_ID=$3 and'.PHP_EOL
            .'c.ASSET_CLASS=$4 and'.PHP_EOL
            .'c.CURRENCY_ID=$5 and'.PHP_EOL
            .'c.CURRENCY_CLASS=$6';

  $r=$ctx->ExecuteQuery
      ($sql,
       $market_id->GetObjectId(),
       $market_id->GetObjectClass(),
       $asset_id->GetObjectId(),
       $asset_id->GetObjectClass(),
       $currency_id->GetObjectId(),
       $currency_id->GetObjectClass());

  if($row=$r->fetch_assoc())
  {
   $result=new CODE_COMMON__DBOBJECT_ID($row['object_id'],$price_class_descr->get_class_id());

   if($r->fetch_assoc())
    throw new Exception('Find multiple records of price.');

   return $result;
  }//if

  //--------------------------------
  COMMON_INIT_CODE::log_line('registration of new price ...');

  //create new coin record
  $new_coin_id=$ctx->GenID($price_class_descr->get_seq_name());

  $result=new CODE_COMMON__DBOBJECT_ID($new_coin_id,$price_class_descr->get_class_id());

  //-------- insert record to CORE.OBJECTS
  self::helper__insert_into_core_objects
   ($ctx,
    $price_class_descr->get_table_descr()->get_parent(),
    $result->GetObjectId(),
    $result->GetObjectClass(),
    0,
    0);

  //-------- insert record to CRYPTOBOT.PRICES
  $sql='insert into '.$price_class_descr->get_table_descr()->get_table_name().PHP_EOL
      .'(OBJECT_ID,'.PHP_EOL
       .'OBJECT_CLASS,'.PHP_EOL
       .'MARKET_ID,'.PHP_EOL
       .'MARKET_CLASS,'.PHP_EOL
       .'ASSET_ID,'.PHP_EOL
       .'ASSET_CLASS,'.PHP_EOL
       .'CURRENCY_ID,'.PHP_EOL
       .'CURRENCY_CLASS)'.PHP_EOL
      .'values($1,$2,$3,$4,$5,$6,$7,$8)';

  $ctx->ExecuteQuery
         ($sql,
          $result->GetObjectId(),
          $result->GetObjectClass(),
          $market_id->GetObjectId(),
          $market_id->GetObjectClass(),
          $asset_id->GetObjectId(),
          $asset_id->GetObjectClass(),
          $currency_id->GetObjectId(),
          $currency_id->GetObjectClass());

  //-------
  return $result;
 }//helper__get_price_id

 //-----------------------------------------------------------------------
 //return
 // - object of CODE_COMMON__DBOBJECT_ID class
 private static function helper__get_coin_id($ctx,$coin_name)
 {
  if(!$coin_name)
   throw new Exception('Empty coin name!');

  $coin_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_COIN);

  $sql='select c.OBJECT_ID from '.$coin_class_descr->get_table_descr()->get_table_name().' c '
      .'where c.OBJECT_CLASS=$1 and c.SHORT_NAME=$2';

  $r=$ctx->ExecuteQuery($sql,$coin_class_descr->get_class_id(),$coin_name);

  if($row=$r->fetch_assoc())
  {
   $result=new CODE_COMMON__DBOBJECT_ID($row['object_id'],$coin_class_descr->get_class_id());

   if($r->fetch_assoc())
    throw new Exception('Find multiple records of coin ['.$coin_name.']');

   return $result;
  }//if

  //--------------------------------
  COMMON_INIT_CODE::log_line('registration of new coin ...');

  //create new coin record
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
       .'SHORT_NAME)'.PHP_EOL
      .'values($1,$2,$3,$4)';

  $ctx->ExecuteQuery($sql,$result->GetObjectId(),$result->GetObjectClass(),$coin_name,$coin_name);

  //-------
  return $result;
 }//helper__get_coin_id

 //-----------------------------------------------------------------------
 //return
 // - object of CODE_COMMON__DBOBJECT_ID class
 private function helper__get_currency_id($ctx,$currency_name)
 {
  if(!$currency_name)
   throw new Exception('Empty currency name!');

  $currency_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__COMMON__CLASS_NAMES::COMMON_CURRENCY);

  $sql='select c.OBJECT_ID from '.$currency_class_descr->get_table_descr()->get_table_name().' c '
      .'where c.OBJECT_CLASS=$1 and c.SHORT_NAME=$2';

  $r=$ctx->ExecuteQuery($sql,$currency_class_descr->get_class_id(),$currency_name);

  if($row=$r->fetch_assoc())
  {
   $result=new CODE_COMMON__DBOBJECT_ID($row['object_id'],$currency_class_descr->get_class_id());

   if($r->fetch_assoc())
    throw new Exception('Find multiple records of currency ['.$currency_name.']');

   return $result;
  }//if

  //--------------------------------
  COMMON_INIT_CODE::log_line('registration of new currency ...');

  //create new currency record
  $new_coin_id=$ctx->GenID($currency_class_descr->get_seq_name());

  $result=new CODE_COMMON__DBOBJECT_ID($new_coin_id,$currency_class_descr->get_class_id());

  //-------- insert record to CORE.OBJECTS
  self::helper__insert_into_core_objects
   ($ctx,
    $currency_class_descr->get_table_descr()->get_parent(),
    $result->GetObjectId(),
    $result->GetObjectClass(),
    0,
    0);

  //-------- insert record to COMMON.CURRENCY
  $sql='insert into '.$currency_class_descr->get_table_descr()->get_table_name().PHP_EOL
      .'(OBJECT_ID,'.PHP_EOL
       .'OBJECT_CLASS,'.PHP_EOL
       .'NAME,'.PHP_EOL
       .'SHORT_NAME)'.PHP_EOL
      .'values($1,$2,$3,$4)';

  $ctx->ExecuteQuery($sql,$result->GetObjectId(),$result->GetObjectClass(),$currency_name,$currency_name);

  //-------
  return $result;
 }//helper__get_currency_id

 //-----------------------------------------------------------------------
 //return
 // - object of CODE_COMMON__DBOBJECT_ID class
 private function helper__get_market_id($ctx,$market_name)
 {
  if(!$market_name)
   throw new Exception('Empty market name!');

  $market_class_descr
   =$ctx->GetClassMng()->get_class_by_name(CODE_DATABASE_DATA__CRYPTOBOT__CLASS_NAMES::CRYPTOBOT_MARKET);

  $sql='select c.OBJECT_ID from '.$market_class_descr->get_table_descr()->get_table_name().' c '
      .'where c.OBJECT_CLASS=$1 and c.SHORT_NAME=$2';

  $r=$ctx->ExecuteQuery($sql,$market_class_descr->get_class_id(),$market_name);

  if($row=$r->fetch_assoc())
  {
   $result=new CODE_COMMON__DBOBJECT_ID($row['object_id'],$market_class_descr->get_class_id());

   if($r->fetch_assoc())
    throw new Exception('Find multiple records of market ['.$market_name.']');

   return $result;
  }//if

  //--------------------------------
  COMMON_INIT_CODE::log_line('registration of new market ...');

  //create new market record
  $new_coin_id=$ctx->GenID($market_class_descr->get_seq_name());

  $result=new CODE_COMMON__DBOBJECT_ID($new_coin_id,$market_class_descr->get_class_id());

  //-------- insert record to CORE.OBJECTS
  self::helper__insert_into_core_objects
   ($ctx,
    $market_class_descr->get_table_descr()->get_parent(),
    $result->GetObjectId(),
    $result->GetObjectClass(),
    0,
    0);

  //-------- insert record to CRYPTOBOT.MARKETS
  $sql='insert into '.$market_class_descr->get_table_descr()->get_table_name().PHP_EOL
      .'(OBJECT_ID,'.PHP_EOL
       .'OBJECT_CLASS,'.PHP_EOL
       .'NAME,'.PHP_EOL
       .'SHORT_NAME)'.PHP_EOL
      .'values($1,$2,$3,$4)';

  $ctx->ExecuteQuery($sql,$result->GetObjectId(),$result->GetObjectClass(),$market_name,$market_name);

  //-------
  return $result;
 }//helper__get_market_id

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
 private static function &helper__get_array_element(&$arr,$key)
 {
  if(!array_key_exists($key,$arr))
   throw new Exception('Key ['.$key.'] not found!');

  return $arr[$key];
 }//helper__get_array_element

 //-----------------------------------------------------------------------
 private static function helper__print_json_structure($json,$step)
 {
  $n=0;

  foreach(array_keys($json) as $k)
  {
   ++$n;

   $x=&$json[$k];

   if(!is_array($x))
    continue;

   COMMON_INIT_CODE::log_line($step.$n.' - ['.$k.'] '.count($x).' element(s)');

   self::helper__print_json_structure($x,'  '.$step.$n.'.');
  }//foreach $k
 }//helper__print_json_structure
};//class LOADER

///////////////////////////////////////////////////////////////////////////////////////////////////

try
{
 COMMON_INIT_CODE::log_line('HELLO FROM ME!');

 //----------
 $ctx=new LOADER_CTX();

 //----------
 LOADER::load_by_usd($ctx,'data\CRYPTO_CURRENCY_CHART--usd-2018_02_12.txt');

 LOADER::load_by_btc($ctx,'data\CRYPTO_CURRENCY_CHART--btc-2018_02_12.txt');

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
