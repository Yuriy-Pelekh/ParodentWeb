<?php
include("../connect.php");

$iCount = 0;

$result = mysql_query("SELECT * FROM patient, archive");
while ($row = mysql_fetch_array($result))
{
    $res = mysql_query("SELECT COUNT(*) FROM reminder WHERE PatId=$row[id]");
    $resRow = mysql_fetch_array($res);
    if ($resRow[0] == 0)
    {
        mysql_query("INSERT INTO reminder (PatId) VALUES ($row[id])");
		$iCount++;
    }
}
echo "Добавлено $iCount нагадувань.";
?>