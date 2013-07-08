<?php
	if (isset($_POST['add']))
	{
		if ($_POST['LastName'] || $_POST['FirstName'] || $_POST['MiddleName'])
		{
			foreach ($_POST as $key => $value)
			{
				$_POST[$key] = safe($value);
			}
			unset($key, $value);

			mysql_query("INSERT INTO patient (LastName, FirstName, MiddleName,
											  BirthDay, BirthMonth, BirthYear,
											  PhoneHome, PhoneWork, PhoneMobile,
											  AddrHome, WorkPlace, Sex, DocId)
									  VALUES ('$_POST[LastName]', '$_POST[FirstName]', '$_POST[MiddleName]',
									  		  '$_POST[BirthDay]', '$_POST[BirthMonth]', '$_POST[BirthYear]',
							   				  '$_POST[PhoneHome]', '$_POST[PhoneWork]', '$_POST[PhoneMobile]',
											  '$_POST[AddrHome]',  '$_POST[WorkPlace]', '$_POST[Sex]', '$_POST[DocId]')");

			$_GET['id'] = mysql_insert_id();
			
			$result = mysql_query("SELECT COUNT(*) FROM reminder WHERE PatId = $_GET[id]");
			$row = mysql_fetch_array($result);
			if ($row[0] != 0)
			{
				mysql_query("DELETE FROM reminder WHERE PatId=$_GET[id]");
			}
			
			if (isset($_POST['BirthdayReminder']))
				$br = 1;
			else
				$br = 0;
			
			if ( !($year < $_POST['VisitYear'] || $month < $_POST['VisitMonth'] || ($day < $_POST['VisitDay'] && $month == $_POST['VisitMonth'])) || !checkdate($_POST['VisitMonth'], $_POST['VisitDay'], $_POST['VisitYear']) )
			{
				$_POST['VisitYear'] = 0;
				$_POST['VisitMonth'] = 0;
				$_POST['VisitDay'] = 0;
			}
			
			mysql_query("INSERT INTO reminder (PatId, visit_day, visit_month, visit_year, birthday_none)
						VALUES($_GET[id], $_POST[VisitDay], $_POST[VisitMonth], $_POST[VisitYear], $br)");
		}
	}
	elseif (isset($_POST['save']))
	{
		if ($_POST['LastName'] || $_POST['FirstName'] || $_POST['MiddleName'])
		{
			foreach ($_POST as $key => $value)
			{
				$_POST[$key] = safe($value);
			}
			unset($key, $value);

			$_GET['id'] = safe($_GET['id']);
				
			mysql_query("UPDATE patient SET LastName='$_POST[LastName]', FirstName='$_POST[FirstName]', MiddleName='$_POST[MiddleName]',
											BirthDay='$_POST[BirthDay]', BirthMonth='$_POST[BirthMonth]', BirthYear='$_POST[BirthYear]',
											PhoneHome='$_POST[PhoneHome]', PhoneWork='$_POST[PhoneWork]', PhoneMobile='$_POST[PhoneMobile]',
											AddrHome='$_POST[AddrHome]', WorkPlace='$_POST[WorkPlace]', Sex='$_POST[Sex]', DocId=$_POST[DocId]
						WHERE id=$_GET[id]");

			if ( isset($_POST['VisitYear']) && isset($_POST['VisitMonth']) && isset($_POST['VisitDay']) )
			{
				if (isset($_POST['BirthdayReminder']))
					$br = 1;
				else
					$br = 0;
				
				if ( !($year < $_POST['VisitYear'] || $month < $_POST['VisitMonth'] || ($day < $_POST['VisitDay'] && $month == $_POST['VisitMonth'])) || !checkdate($_POST['VisitMonth'], $_POST['VisitDay'], $_POST['VisitYear']) )
				{
					$_POST['VisitYear'] = 0;
					$_POST['VisitMonth'] = 0;
					$_POST['VisitDay'] = 0;
				}
				
				mysql_query("UPDATE reminder
							SET visit_day=$_POST[VisitDay],
								visit_month=$_POST[VisitMonth],
								visit_year=$_POST[VisitYear],
								birthday_none=$br
							WHERE PatId=$_GET[id]");
			}
		}
	}
	elseif (isset($_POST['delete']))
	{
		$_GET['id'] = safe($_GET['id']);
		mysql_query("DELETE FROM patient WHERE id=$_GET[id]");
		mysql_query("DELETE FROM archive WHERE id=$_GET[id]");
		mysql_query("DELETE FROM blacklist WHERE PatId=$_GET[id]");
		mysql_query("DELETE FROM reception WHERE PatId=$_GET[id]");
		mysql_query("DELETE FROM reminder WHERE PatId=$_GET[id]");
	}
	elseif (isset($_POST['archive']))
	{
		$_GET['id'] = safe($_GET['id']);

		mysql_query("INSERT INTO archive (id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
											PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId)
							SELECT id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
											PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId
							FROM patient WHERE id=$_GET[id]");

		mysql_query("DELETE FROM patient WHERE id=$_GET[id]");
		
		mysql_query("UPDATE reminder
					SET visit_day=0,
						visit_month=0,
						visit_year=0,
						visit_miss=0,
						visit_your_own=0,
						visit_done=0
					WHERE PatId=$_GET[id]");
	}
	elseif (isset($_POST['visit_your_own_plus']))
	{
		$_GET['id'] = safe($_GET['id']);
		$vD = $_POST['VisitDay'];
		$vM = $_POST['VisitMonth'];
		$vY = $_POST['VisitYear'] + 1;
		
		while ( !checkdate($vM, $vD, $vY) )
			$vD--;	
		
		mysql_query("UPDATE reminder
						SET visit_day=$vD,
							visit_month=$vM,
							visit_year=$vY,
							visit_your_own=1
						WHERE PatId=$_GET[id]");
	}
	elseif (isset($_POST['visit_your_own_minus']))
	{
		$_GET['id'] = safe($_GET['id']);
		$vD = $_POST['VisitDay'];
		$vM = $_POST['VisitMonth'];
		$vY = $_POST['VisitYear'] - 1;
		
		while ( !checkdate($vM, $vD, $vY) )
			$vD--;	
		
		mysql_query("UPDATE reminder
						SET visit_day=$vD,
							visit_month=$vM,
							visit_year=$vY,
							visit_your_own=0
						WHERE PatId=$_GET[id]");
	}
	elseif (isset($_POST['not_phoned_plus']))
	{
		$_GET['id'] = safe($_GET['id']);
		mysql_query("UPDATE reminder SET not_phoned=1 WHERE PatId=$_GET[id]");
	}	
	elseif (isset($_POST['not_phoned_minus']))
	{
		$_GET['id'] = safe($_GET['id']);
		mysql_query("UPDATE reminder SET not_phoned=0 WHERE PatId=$_GET[id]");
	}	
	elseif (isset($_POST['add_reception']))
	{
		foreach ($_POST as $key => $value)
		{
			$_POST[$key] = safe($value);
		}
		unset($key, $value);
		$_GET['id'] = safe($_GET['id']);

		mysql_query("INSERT INTO reception (DocId, PatId, day, month, year)
				VALUES ('$_POST[ReceptionDoctor]', '$_GET[id]', '$_POST[ReceptionDay]', '$_POST[ReceptionMonth]', '$_POST[ReceptionYear]')");

		$vD = $_POST['ReceptionDay'];
		$vM = $_POST['ReceptionMonth'];
		$vY = $_POST['ReceptionYear'];

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
			$vD = $_POST['ReceptionDay'];
			$vM = $_POST['ReceptionMonth'];
			$vY = $_POST['ReceptionYear'] + 1;
		}
		
		while ( !checkdate($vM, $vD, $vY) )
			$vD--;

		// above 12 month
		if ( !($vY > $year || ($vY == $year && $vM > $month) || ($vY == $year && $vM == $month && $vD > $day)) )
		{
			$vD = $day + 2;
			$vM = $month;
			$vY = $year;

			if ($vD > 28)
			{
				$vD = 1;
				$vM++;
				
				if ($vM > 12)
				{
					$vM = 1;
					$vY++;
				}
			}
		}

		mysql_query("UPDATE reminder SET visit_day=$vD,
										 visit_month=$vM,
										 visit_year=$vY,
										 visit_miss=0,
										 not_phoned=0,
										 visit_your_own=0,
										 visit_done=0
					WHERE PatId=$_GET[id]");
	}
	elseif (isset($_POST['delete_reception']))
	{
		$_POST['ReceptionId'] = safe($_POST['ReceptionId']);
		mysql_query("DELETE FROM reception WHERE id=$_POST[ReceptionId]");
	}
	elseif (isset($_POST['add_blacklist']))
	{
		$_GET['id'] = safe($_GET['id']);
		$_POST['Description'] = safe($_POST['Description']);
		mysql_query("INSERT INTO blacklist (PatId, Description) VALUES ('$_GET[id]', '$_POST[Description]')");
		
		mysql_query("UPDATE reminder
					SET visit_day=0,
						visit_month=0,
						visit_year=0,
						visit_miss=0,
						visit_your_own=0,
						visit_done=0,
						birthday_none=1
					WHERE PatId=$_GET[id]");
	}
	elseif (isset($_POST['save_blacklist']))
	{
		$_GET['id'] = safe($_GET['id']);
		$_POST['Description'] = safe($_POST['Description']);
		mysql_query("UPDATE blacklist SET Description='$_POST[Description]' WHERE PatId=$_GET[id]");	
	}
	elseif (isset($_POST['delete_blacklist']))
	{
		$_GET['id'] = safe($_GET['id']);
		mysql_query("DELETE FROM blacklist WHERE PatId=$_GET[id]");
		mysql_query("UPDATE reminder SET birthday_none=0 WHERE PatId=$_GET[id]");
	}
?>