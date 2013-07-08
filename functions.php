<?php
function safe($str)
{
	$str = mysql_real_escape_string($str);
	
	do {
		$str = str_replace('  ', ' ', $str, $count);
	} while ($count);
  
  	if ( !get_magic_quotes_gpc() )
	{
    	$str = addslashes($str);
  	}
  	
	return $str;
}

function cleanup($str)
{
	$str = str_replace('\r\n', chr(13), $str);
	$str = stripslashes($str);
	return $str;
}
?>