<?php
	if (isset($_POST['save']))
	{
		if ($_POST['LastName'] || $_POST['FirstName'] || $_POST['MiddleName'])
		{
			foreach ($_POST as $key => $value)
			{
				$_POST[$key] = safe($value);
			}
			unset($key, $value);

			$_GET['id'] = safe($_GET['id']);
				
			mysql_query("UPDATE archive SET LastName='$_POST[LastName]', FirstName='$_POST[FirstName]', MiddleName='$_POST[MiddleName]',
											BirthDay='$_POST[BirthDay]', BirthMonth='$_POST[BirthMonth]', BirthYear='$_POST[BirthYear]',
											PhoneHome='$_POST[PhoneHome]', PhoneWork='$_POST[PhoneWork]', PhoneMobile='$_POST[PhoneMobile]',
											AddrHome='$_POST[AddrHome]', WorkPlace='$_POST[WorkPlace]', Sex='$_POST[Sex]', DocId=$_POST[DocId]
						WHERE id=$_GET[id]");
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

		mysql_query("INSERT INTO patient (id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
											PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId)
							SELECT id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
											PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId
							FROM archive WHERE id=$_GET[id]");

		mysql_query("DELETE FROM archive WHERE id=$_GET[id]");

		mysql_query("UPDATE reminder
					SET visit_day=0,
						visit_month=0,
						visit_year=0,
						visit_miss=0,
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