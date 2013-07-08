<?php
include("../connect.php");

$result = mysql_query("SELECT PatId FROM blacklist ORDER BY PatId");
$iCount = 0;
while ($row = mysql_fetch_array($result))
{
	mysql_query("UPDATE reminder SET visit_day=0, visit_month=0, visit_year=0, visit_miss=0, visit_your_own=0, visit_done=0, birthday_none=1 WHERE PatId=$row[PatId]");
	$iCount++;
}
echo "Оновлено $iCount записів";
?>