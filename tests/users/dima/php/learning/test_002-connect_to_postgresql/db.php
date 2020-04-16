<?php

class DB
{
 private $m_dbconn;

 private function __construct($connection_string)
 {
  $this->m_dbconn = pg_connect($connection_string);
 }//__construct

 public function __destruct()
 {
  pg_close($this->m_dbconn);
 }//__destruct
 
 //---------------------------------------------------------------
 public static function create_from_cn_str($connection_string)
 {
  return new self($connection_string);
 }//create_from_cn_str
 
 //---------------------------------------------------------------
 public static function create($server,$database,$user,$password)
 {
  $connection_string
   =sprintf('host=%s dbname=%s user=%s password=%s',
            $server,
            $database,
            $user,
            $password);

  return self::create_from_cn_str($connection_string);
 }//create
};//class DB

?>
