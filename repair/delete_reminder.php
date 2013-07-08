<?php
include("../connect.php");

$result = mysql_query("SELECT PatId FROM reminder ORDER BY PatId");
$iCount = 0;
while ($row = mysql_fetch_array($result))
{
	$res = mysql_query("SELECT COUNT(*) FROM patient WHERE id=$row[PatId]");
	$r = mysql_fetch_array($res);
	if ($r[0] == 0)
	{
		mysql_query("DELETE FROM reminder WHERE PatId=$row[PatId]");
		$iCount++;
	}	
}
echo "Видалено $iCount нагадувань";
?>