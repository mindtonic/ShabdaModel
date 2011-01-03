<?php

class DatabaseManager
{
  static private $instance = null;
  private $observers = array();
  
  private function __construct() { }
  
  static public function instance()
  {
    if (self::$instance == null)
    {
      self::$instance = new DatabaseManager();
      self::$instance->initialize();
    }
    return self::$instance;
  }
  
  #
  # API
  #
  
  static public function query($sql)
  {
    $db = DatabaseManager::instance();
    return $db->execute_query($sql);
  }
  
  #
  # Private Methods
  #
  
  private function execute_query($sql)
  {
    $this->observe($sql);
    return mysql_query($sql);
  }
  
  private function initialize()
  {
    $conn = mysql_connect(DB_HOST,DB_USER,DB_PWORD);
    if (!$conn) shabda_database_error("Unable to connect to Database");
    if (!mysql_select_db(DB_NAME)) shabda_database_error("Unable to select Database (".DB_NAME.")");
    
    $this->observers[] = new SystemObserver; 
  }
  
  private function observe($sql)
  {
  	foreach($this->observers as $observer)
  		$observer->call($sql);
  }
}

function shabda_database_error($message)
{
  $trace = debug_backtrace();
  trigger_error($message.': '.mysql_error().' -- '.$name.' in '.$trace[0]['file'].' on line '.$trace[0]['line'], E_USER_NOTICE);
  return null;
}

?>