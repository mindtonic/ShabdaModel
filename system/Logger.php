<?php

class Logger
{
	private $log;
	private $directory = '../logs/';
	private $available_logs = array('access','errors','sql','system','user');
	
	function __construct($log, $directory = null)
	{
		$this->log = $log;
		if ($directory) $this->directory = $directory;
		
		$this->check_directory();
		$this->check_log();
	}
	
	private function check_directory()
	{
		if (!is_dir($this->directory))
			mkdir($this->directory);
	}
	
	private function check_log()
	{
		if (!in_array(strtolower($this->log), $this->available_logs))
			$this->system_error(ucwords($this->log)." is not a valid system log type. Choices are: ".ucwords(implode(', ',$this->available_logs)));
	}
		
	public function enter($content, $time = true)
	{
		$content = sprintf("%'.-80s", $content);
		if ($time && (strlen($content)  <= 80)) $content .= "TIME: ".date('Y-m-d H:i:s', time())."....IP: ".$_SERVER['REMOTE_ADDR'];
		$this->to_file($content."\n");
	}
	
	public function close_entry()
	{
		$this->to_file("\n\n");
	}
	
	public function to_file($content)
	{
		if (!$handle = fopen($this->directory.$this->log.'.log', 'a'))
		{
				 $this->system_error("Cannot open log (".ucwords($this->log).")");
				 exit;
		}		
		if (fwrite($handle, $content) === FALSE) 
		{
				$this->system_error("Cannot write to log (".ucwords($this->log).")");
				exit;
		}		
		fclose($handle);	
	}
	
	#############	
	
	static function log_shabda_error()
	{
		$logger = new Logger('errors');
		$registry = Registry::instance();
		
		$logger->enter($registry->shabda_error());
	}
}

?>
