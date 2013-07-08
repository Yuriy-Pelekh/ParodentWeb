<?php
	$dbHost = "localhost";
	$db = "parodent";
	$dbUser = "root";
	$dbPass = "";

	$con = mysql_connect($dbHost,$dbUser,$dbPass);

	if ( !$con ) {
		die('Could not connect to server: ' . mysql_error());
	}

	mysql_select_db($db,$con);

	mysql_query('SET NAMES utf8');
?>