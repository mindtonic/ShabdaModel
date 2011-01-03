<?php 

define("DB_NAME", "shabda_model");   # Production Database Name
define("DB_USER", "root");           # Production Database Username
define("DB_PWORD", "root");          # Production Database Password
define("DB_HOST", "localhost");      # Production Database Host

foreach (glob("system/*.php") as $filename) { require_once $filename; }
foreach (glob("models/*.php") as $filename) { require_once $filename; }

?>