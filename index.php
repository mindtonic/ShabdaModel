<?php

require_once('config.php');
$models = Factory::Find('quotes');

$output = '
<h1>Testing Shabda Model</h1>
<div class="pageInfo">I found '.count($models).' Records.</div>';

if ($models) foreach($models as $quote)
{
	$output .= '
<div class="quote">
'.$quote->quote.'
<div class="source">'.$quote->source.'</div>
</div>';
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Testing Shabda Model</title>
	<style type="text/css">
	body { color: white; background-color: #212121; line-height: 20px; font-size: 14px; font-family: Verdana, Arial, sans-serif;}
	h1 { border-width: 2px; border-style: inset; color: white; background-color: #212121; text-align: center; padding: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; }
	#master { border-color: #ccc; border-width: 5px; border-style: outset; color: #212121; background-color: white; width: 700px; margin: 0 auto; padding: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; }
	.pageInfo { text-align: center; font-weight: bold;}
	.quote {padding: 10px 0; margin: 10px 0; border-bottom: solid 1px #ccc;}
	.source {text-align: right; font-size: 11px; margin: 10px 0;}
	</style>
	</head>
	<body>
	<div id="master">	
		<?php echo $output; ?> 
	</div>
  </body>
</html>