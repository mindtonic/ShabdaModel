<?php

interface Observer
{
	public function call($message);
}

class SystemObserver implements Observer
{

  #
  # API
  #

  public function call($message)
  {
    $this->record($message);
  }
    
  public function enter($message)
  {
    $this->record($message);
  }
  
  #
  # Utilities
  #
  
  private function record($message)
  {
    $message = sprintf("%'.-80s", $message);
    if (strlen($message)  <= 80) $message .= "TIME: ".date('Y-m-d H:i:s', time())."....IP: ".$_SERVER['REMOTE_ADDR'];
  
    $handle = fopen('system.log', 'a');
    fwrite($handle, $message."\n");
    fclose($handle);
  }
}

?>