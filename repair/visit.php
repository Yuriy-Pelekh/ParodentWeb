<?php
include("../connect.php");
list($day, $month, $year) = explode(" ", date("j n Y"));

$result = mysql_query("SELECT id FROM patient ORDER BY id");

$archiveCount = 0;
$visitCount = 0;

while ($row = mysql_fetch_array($result))
{
	$res = mysql_query("SELECT year, month, day FROM reception WHERE PatId=$row[id] ORDER BY year DESC");
	$r = mysql_fetch_array($res);
	
	if ($r['year'] < $year-4)
	{
		mysql_query("INSERT INTO archive (id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
											PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId)
							SELECT id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
											PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId
							FROM patient WHERE id=$row[id]");
	
		mysql_query("DELETE FROM patient WHERE id=$row[id]");
		
		mysql_query("UPDATE reminder
					SET visit_day=0,
						visit_month=0,
						visit_year=0,
						visit_miss=0,
						visit_your_own=0,
						visit_done=0
					WHERE PatId=$row[id]");
		
		$archiveCount++;
	}
	elseif ($r['year'] >= $year-4)
	{
		$vD = $r['day'];
		$vM = $r['month'];
		$vY = $r['year'];
		
		while ($vY < $year)
		{
			$vY++;
		}
		
		if ($vM<=$month && $vD<=$day)
			$vY++;
		
		while ( !checkdate($vM, $vD, $vY) )
			$vD--;
	
		$res1 = mysql_query("SELECT visit_year, visit_month, visit_day FROM reminder WHERE PatId=$row[id]");
		$rr = mysql_fetch_array($res1);

		if ( $rr['visit_year'] == 0 && $rr['visit_month'] == 0 && $rr['visit_day'] == 0 )
		{
			mysql_query("UPDATE reminder SET visit_day=$vD,
											 visit_month=$vM,
											 visit_year=$vY,
											 visit_miss=0,
											 visit_done=0
						WHERE PatId=$row[id]");
			$visitCount++;
		}
	}
}

echo "Оновлено $visitCount визитів<br />";
echo "Перенесено в архів $archiveCount пацієнтів";
?>