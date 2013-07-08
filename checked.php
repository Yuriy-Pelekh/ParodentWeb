<?php
session_start();

include("connect.php");
include("functions.php");

mysql_query("UPDATE reminder SET $_GET[table]=$_GET[checked] WHERE PatId=$_GET[id]");
?>