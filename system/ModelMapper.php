<?php

class ModelMapper
{
  static private $instance = null;
  private $model_cache = array();
  private $db;
  private $lazy_load = true;
  private $observers = array();
  
  private function __construct() { }
  
  #
  # Static API Methods
  #
  
  static public function instance()
  {
    if (self::$instance == null)
    {
      self::$instance = new ModelMapper();
      self::$instance->initialize();  
    }
    return self::$instance;
  }
  
  static public function get_model($name)
  {
    $mapper = ModelMapper::instance();
    return $mapper->get($name);
  }
  
  static public function load_model($name, $id)
  {
    $mapper = ModelMapper::instance();
    return $mapper->load($name, $id);  
  }
  
  #
  # Instance API Methods
  #
  
  public function get($name)
  {
    if (class_exists($name))
    {
      if (!array_key_exists($name, $this->model_cache))
      {
      	$this->observe("Building ".$name);
        $this->model_cache[$name] = new $name;
      }
      return clone $this->model_cache[$name];
    }
  }
  
  public function load($name, $id)
  {
    $model = $this->get($name);
    $model->load($id);
    return $model;
  }

  public function __set($label, $object)
  {
    shabda_mapper_error("_SET called on Mapper. All Models must be set internally. Use get_model");
  }

  public function __unset($name)
  {
    unset($this->model_cache[$name]);
  }

  public function __get($name)
  {
    return $this->get_model($name);
  }

  public function __isset($name)
  {
    return isset($this->model_cache[$name]);
  }

  #
  # Internal Methods
  #

  private function initialize()
  {
    if ($this->db == null)
      $this->db = DatabaseManager::instance();
    if (!$this->lazy_load)
      $this->load_all_models();
      
    $this->observers[] = new SystemObserver;
  }
  
  private function load_all_models()
  {
    $sql = "SHOW TABLES FROM `".DB_NAME."`";
    $data = $this->db->query($sql);
    if (mysql_num_rows($data) > 0)
      while (list($table) = mysql_fetch_array($data))
        $this->get_model($table);
  }
  
  private function observe($message)
  {
  	foreach($this->observers as $observer)
  		$observer->call($message);
  }
}

function shabda_mapper_error($message)
{
  $trace = debug_backtrace();
  trigger_error($message.': '.$name.' in '.$trace[0]['file'].' on line '.$trace[0]['line'], E_USER_NOTICE);
  return null;
}

?>
