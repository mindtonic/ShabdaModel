<?php

class Factory
{

  #
  # Find
  #

  // Generic Find Function to handle all possible options
  static public function Find($model, $params = null)
  {
    // Default Values
    $sql_where = "";
    $sql_group = "";
    $sql_order = "";
    $sql_limit = "";
  
    if (is_array($params))
    {
      extract ($params);
      $sql_where = $where ? " WHERE ".$where : "";
      $sql_group = $group ? " GROUP BY ".$group : "";
      $sql_order = $order ? " ORDER BY ".$order : "";
      $sql_limit = $limit ? " LIMIT ".$limit : "";
    }
    elseif (is_numeric($params))
    {
      $sql_where = " WHERE `id` = ".$params;
    }

    // Build SQL Query
    $sql = "SELECT * FROM `".strtolower($model)."`".$sql_where.$sql_group.$sql_order.$sql_limit;
    $data = Factory::query($sql);

    if (@mysql_num_rows($data))
    {
      $collection = array();
      while ($results = mysql_fetch_array($data,MYSQL_ASSOC))
      {
        $object = Factory::get_model($model);
        $object->set_attributes($results);
        if ($associations) $object->load_associations();
        $collection[] = $object;
      }
    }

    if (count($collection) >= 1) return $collection;
    else return false;
  }
  
  #
  # Utilities
  #
  
  static private function query($sql)
  {
    return DatabaseManager::query($sql);
  }

  static private function get_model($name)
  {
    return ModelMapper::get_model($name);
  }
}

?>
