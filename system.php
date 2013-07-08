<?php
if (!isset($_SESSION['check_db']))
{
	$_SESSION['check_db'] = 0;
}
else
{
	$_SESSION['check_db']++;
}

if ($_SESSION['check_db'] == 0)
{

	$uM = $month - 1;
	$aM = $month + 1;
	
	$result = mysql_query("SELECT patient.id
						   FROM patient, reminder
						   WHERE (patient.BirthMonth<$uM OR
								  patient.BirthMonth>$aM) AND
								  patient.id=reminder.PatId AND
								  birthday_done=1");
	while ($row = mysql_fetch_array($result))
	{
		mysql_query("UPDATE reminder SET birthday_done=0 WHERE PatId=$row[id]");
	}

//================================================================================

	$uM--;
	if ($uM < 1)
	{
		$uM = 12 + $uM;
		$y = $year - 1;
	}
	else
	{
		$y = $year;
	}
	
	$result = mysql_query("SELECT * FROM reminder WHERE visit_month<=$uM AND visit_year=$y");
	while ($row = mysql_fetch_array($result))
	{
		$vD = $row['visit_day'];
		$vM = $row['visit_month'];
		$vY = $row['visit_year'];
		
		// under 6 month
		if (($vM += 6) > 12)
		{
			$vM -= 12;
			$vY++;
		}
		
		while ( !checkdate($vM, $vD, $vY) )
			$vD--;
		
		// 6 to 12 month
		if ( !($vY > $year || ($vY == $year && $vM > $month) || ($vY == $year && $vM == $month && $vD > $day)) )
		{
			$vD = $row['visit_day'];
			$vM = $row['visit_month'];
			$vY = $row['visit_year'] + 1;
		}
		
		while ( !checkdate($vM, $vD, $vY) )
			$vD--;
		
		$vMissCount = $row['visit_miss'] + 1;
		if ($vMissCount < 3)
		{
			mysql_query("UPDATE reminder SET visit_day=$vD, visit_month=$vM, visit_year=$vY, visit_miss=$vMissCount, visit_done=0 WHERE PatId=$row[PatId]");
		}
		else
		{
			mysql_query("UPDATE reminder SET visit_day=0, visit_month=0, visit_year=0, visit_miss=$vMissCount, visit_done=0 WHERE PatId=$row[PatId]");
		}
	}


	// above 1 year
	$result = mysql_query("SELECT * FROM reminder WHERE visit_year<$y AND visit_year!=0");
	while ($row = mysql_fetch_array($result))
	{
			$vD = $row['visit_day'];
			$vM = $row['visit_month'];
			$vY = $row['visit_year'];
			
			while ($vY < $year)
			{
				$vY++;
			}
			while ( !checkdate($vM, $vD, $vY) )
				$vD--;

			mysql_query("UPDATE reminder SET visit_day=$vD, visit_month=$vM, visit_year=$vY, visit_miss=1, visit_done=0 WHERE PatId=$row[PatId]");

	}
//================================================================================

	$result = mysql_query("SELECT id FROM patient ORDER BY id");
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
		}
/* оновлення нагадувань для пацієнтів, які були не більше ніж 5 років тому на прийомі і не мають нагадування про візит
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
							echo "-";
			}
		}*/
	}
}
?>