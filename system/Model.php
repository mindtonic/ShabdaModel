<?php

class Model
{
  public $table;
  public $table_data = array();
  public $defaults = array();
  protected $properties = array();
  protected $results;
  protected $errors = array();

  #
  # Initialization
  #

  // Label the product on instate
  function __construct($id = null)
  {
    // Set Table
    $this->table = get_class($this);
    $this->define_model();
    // Install any predefined custom attributes
    $this->custom_accessors();
    // Load Self
    if ($id) $this->load($id);
  }

  #
  # Define Model
  #
  
  private function define_model()
  {
    if (!$this->properties)
    {
      $sql = "SHOW FIELDS FROM `".$this->table."`";
      $data = $this->query($sql);
      if (mysql_num_rows($data))
      {
        while ($results = mysql_fetch_array($data,MYSQL_ASSOC))      
        {
          $this->properties[$results['Field']] = "";
          $this->table_data[$results['Field']] = $results['Type'];
          $this->defaults[$results['Field']] = $results['Default'];
        }
        # Strip out unset default values
        $this->defaults = array_filter($this->defaults);
      }
      else Errors::system_error('MODEL: Table '.$this->table.' does not exist.');
    }
  }

  #
  # Get and Set
  #
  
  // Custom Accessors
  protected function custom_accessors()
  {
    if ($this->accesor_attributes)
      foreach ($this->accesor_attributes as $att)
        $this->properties[$att] = "";  
  }
  
  // Set value
  public function set_value($att,$value)
  {
    $this->properties[$att] = trim(stripslashes($value));
  }
  
  public function set_object($att,$object)
  {
    $this->properties[$att] = $object;  
  }

  // Get value
  public function value($att)
  {
    return array_key_exists($att, $this->properties) ? $this->properties[$att] : false;
  }
  
  // Unset value
  public function unset_value($att)
  {
    if (array_key_exists($att, $this->properties))
      $this->properties[$att] = null;
  }
  
  // Set Attributes for update
  public function set_attributes($attributes)
  {
    if (is_array($attributes) and count($attributes) > 0)
      foreach ($attributes as $key => $value)
        $this->set_value($key,$value);
  }
  
  // Errors
  public function errors()
  {
    if (isset($this->errors)) return $this->errors;
  }
  
  public function errors_on($key)
  {
    if ($this->errors[$key])
      return $this->errors[$key];
    else return false;
  }
  
  public function set_error($key, $value)
  {
    $this->errors[$key] = $value;
  }
  
  public function valid()
  {
    return (count($this->errors) > 0 ? false : true);
  }  
  
  #
  # Magic Property Functions
  #

  public function __set($label, $value)
  {
    if (is_object($value)) $this->properties[$label] = $value;
    else $this->properties[$label] = trim(stripslashes($value));
  }

  public function __unset($label)
  {
    unset($this->properties[$label]);
  }

  public function __get($label)
  {
    return array_key_exists($label, $this->properties) ? $this->properties[$label] : false;
  }

  public function __isset($label)
  {
    return isset($this->properties[$label]);
  }

  #
  # Loading
  #
  
  // Load
  public function load($id)
  {
    // Query
    $sql = "SELECT * FROM `".$this->table."` WHERE `id` = '".$id."' LIMIT 1";
    $data = $this->query($sql);
    if (mysql_num_rows($data))
    {
      // Loop and assign
      foreach (mysql_fetch_array($data,MYSQL_ASSOC) as $key => $value)
        $this->set_value($key,$value);
      // Check for onLoad functions
      if (method_exists($this, 'on_load')) $this->on_load();
      // Return
      return true;
    }
    else return false;
  }
  
  public function load_all($associations = false)
  {
    $objects = array();
    $sql = 'SELECT * FROM `'.$this->table.'`';
    $data = $this->query($sql);
    if (mysql_num_rows($data))
    {
      while ($results = mysql_fetch_array($data))
      {
        $object = $this->set_model($this->table, $results);
        if ($associations) $object->load_associations();
        $objects[] = $object;
      }
    }
    return $objects;
  }

  #
  # Saving
  #
  
  // Save Object
  public function save()
  {
    // Before Save - set in models
    if (method_exists($this, 'before_save')) $this->before_save();
    // Validate
    if (!$this->validate()) return false;
    // Format
    $this->format();
    // Assemble
    $this->is_editing() ? $this->update() : $this->create();
  }
  
  // Processing Method for a new Object
  private function create()
  {
    // Assign
    foreach ($this->properties as $key => $value)
    {
      if (array_key_exists($key, $this->table_data) && $key != 'id')
      {
        $columns[] = "`".$key."`";
        $data[] = "'".$this->sanitize_query($value)."'";
      }
    }
    // Assemble
    $sql = "INSERT INTO `".$this->table."` (".implode(",",$columns).") VALUES (".implode(",",$data).")";
    // Query
    $data = $this->query($sql);
    // Set ID
    $this->set_value('id',mysql_insert_id());
  }
  
  // Processing Method for an existing Object
  private function update()
  {
    // Assign
    foreach ($this->properties as $key => $value)
      if (array_key_exists($key, $this->table_data) && $key != 'id')
        $update[] = "`".$key."` = '".$this->sanitize_query($value)."'";
    // Assemble
    $sql = "UPDATE `".$this->table."` SET ".implode(",",$update)." WHERE `id` = '".$this->id."'";
    // Query
    $data = $this->query($sql);
  }

  // Sanitize
  private function sanitize_query($value)
  {
    return trim(addslashes($value));
  }
  
  // Is Editing?
  public function is_editing()
  {
    return $this->id ? true : false;
  }
    
  #
  # Destroy
  #
  
  // Destroy
  public function destroy()
  {
    // Destroy self
    $sql = "DELETE FROM `".$this->table."` WHERE `id` = '".$this->id."' LIMIT 1";
    $data = $this->query($sql);
    if (mysql_affected_rows()) $this->results = "Delete successful.";
  }
  
  #
  # Query Methods
  #
  
  protected function query($sql)
  {
    return DatabaseManager::query($sql);
  }
  
  protected static function static_query($sql)
  {
    return DatabaseManager::query($sql);
  }
  
  #
  # Model Mapper Proxies
  #
  
  public function get_model($name)
  {
    return ModelMapper::get_model($name);
  }

  public function load_model($name, $id)
  {
    return ModelMapper::load_model($name, $id);
  }
  
  public function set_model($name, $attributes)
  {
    $model = $this->get_model($name);
    $model->set_attributes($attributes);
    return $model;
  }

  #
  # Utilities
  #

  public function increment($field)
  {  
    $this->$field++;
  }
  
  public function decrement($field)
  {
    $this->$field--;
  }
    
  // Convert boolean to tiny int
  protected function boolean($list)
  {
    foreach ($list as $bool)
      $this->set_value($bool, $this->$bool ? 1 : 0); 
  }
  
  #
  # Validations
  #
  
  public function validate()
  {
     // Call validations in order of lesser importance. Error message is set by final method.
    if (method_exists($this, 'custom_validations')) $this->custom_validations();     
     if ($this->field_lengths) $this->validate_length();
    if ($this->confirmations) $this->validate_confirmations();
    if ($this->unique_fields) $this->validate_unique();
    // Automatically validate urls and email
    $this->validate_email();
    $this->validate_url();
    if ($this->required_fields) $this->validate_required();
    if ($this->agreements) $this->validate_agreements();
    
    return $this->valid();
  }
  
  private function validate_required()
  {
    foreach ($this->required_fields as $field)
      if (!isset($this->$field) || $this->$field == "")
        $this->errors[$field] = $this->message($field)." is required";
  }
  
  private function validate_unique()
  {
    foreach ($this->unique_fields as $field)
    {
      $sql = "SELECT `id` FROM `".$this->table."` WHERE `".$field."` = '".$this->$field."' AND `id` != '".$this->id."' LIMIT 1";
      $data = $this->query($sql);
      if (mysql_num_rows($data))
        $this->errors[$field] = $this->message($field)." must be unique.  Unfortunately, that one is already in use.";
    }  
  }
 
  private function validate_length()
  {
    foreach ($this->field_lengths as $field => $length)
      if (strlen($this->$field) > $length)
        $this->errors[$field] = $this->message($field)." is too long. Please limit it to ".$length." characters.";    
  }
  
  private function validate_confirmations()
  {
    foreach ($this->confirmations as $confirmation)
    {
      $confirm = $confirmation.'_confirmation';
      if ($this->$confirmation != $this->$confirm)
      {
        $this->errors[$confirmation] = ucwords($this->message($confirmation))." does not match.";    
        $this->errors[$confirm] = $this->message($confirm)." does not match.";
      }
    }
  }
  
  private function validate_agreements()
  {
    foreach ($this->agreements as $agreement)
      if (!$this->$agreement)
        $this->errors[$agreement] = "you must agree to the ".$this->message($agreement).".";
  }

  public function validate_email()
  {
    if (array_key_exists('email', $this->properties) && $this->email)
    {
      $regex = "/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i";
      if (!preg_match($regex, $this->email))
        $this->errors['email'] = "this email address does not appear to be valid.";
    }
  }
  
  public function validate_url()
  {
    if (array_key_exists('url', $this->properties))
      $this->validate_custom_url('url');
  }
  
  protected function validate_custom_url($key)
  {
    if ($this->$key)
    {
      // Check for http and add if necessary
      if (!preg_match('/^(http|https)+(:\/\/)/i',$this->$key))
        $this->set_value($key,'http://'.$this->$key);
      // Check url with regex
      $regex = "/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i";
      if (!preg_match($regex, $this->$key))
        $this->errors[$key] = "this url does not appear to be valid.";  
    }
  }
  
  #
  # Validation Reflection
  #
  
  public function is_required($field)
  {
    if ($this->required_fields) return in_array($field, $this->required_fields) ? true : false;
    else return false;
  }

  public function is_unique($field)
  {
    if ($this->unique_fields) return in_array($field, $this->unique_fields) ? true : false;
    return false;
  }

  public function has_limit($field)
  {
    return ($this->field_lengths ? (array_key_exists($field, $this->field_lengths) ? $this->field_lengths[$field] : false) : false);
  }
  
  #
  # Formatting
  #
  
  public function format()
  {
    if (method_exists($this, 'custom_formatting')) $this->custom_formatting(); 
    $this->is_editing() ? $this->updated_at() : $this->created_at();
    if ($this->titleize) $this->titleize();
    $this->health_inspector();
  }

  protected function titleize()
  {
    foreach ($this->titleize as $value)
      $this->set_value($value, ucwords(preg_replace('/_/',' ',$this->$value)));
  }
  
  private function message($message)
  {
    return ucwords(preg_replace('/_/', ' ', $message));
  }
  
  #
  # Magic Fields
  #
  
  private function created_at()
  {
    if (array_key_exists('created_at', $this->properties))
      $this->set_value('created_at', date('Y-m-d H:i:s', time()) );
    // Populate updated date for sorting
    $this->updated_at();
  }
  
  private function updated_at()
  {
    if (array_key_exists('updated_at', $this->properties))
      $this->set_value('updated_at', date('Y-m-d H:i:s', time()) );
  }
  
  #
  # User Input Sanitization
  #

  protected function health_inspector()
  {
    foreach ($this->table_data as $key => $value)
    {
      if ($value == 'text')
        $this->set_value($key, $this->sanitize_text($this->$key));
      if (strstr($value, 'varchar'))
        $this->set_value($key, $this->sanitize_string($this->$key));      
    }
  }
  
  protected function sanitize_text($text)
  {
    return strip_tags($text, '<p><a><img><div><h1><h2><h3><h4><b><u><i><ul><ol><li><br><br /><blockquote><table><th><td><tr><strong><span>');
  }
  
  protected function sanitize_string($text)
  {
    return strip_tags($text);  
  }
}

function shabda_model_error($message)
{
  $trace = debug_backtrace();
  trigger_error($message.': '.$name.' in '.$trace[0]['file'].' on line '.$trace[0]['line'], E_USER_NOTICE);
  return null;
}

?>
