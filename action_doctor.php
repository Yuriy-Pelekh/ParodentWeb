<?php
	if (isset($_POST['add']))
	{
		if ($_POST['LastName'] || $_POST['FirstName'] || $_POST['MiddleName'])
		{
			$_POST['LastName'] = safe($_POST['LastName']);
			$_POST['FirstName'] = safe($_POST['FirstName']);
			$_POST['MiddleName'] = safe($_POST['MiddleName']);
			mysql_query("INSERT INTO doctor (LastName, FirstName, MiddleName) VALUES ('$_POST[LastName]', '$_POST[FirstName]', '$_POST[MiddleName]')");
			$_GET['id'] = mysql_insert_id();
		}
	}
	elseif (isset($_POST['save']))
	{
		if ($_POST['LastName'] || $_POST['FirstName'] || $_POST['MiddleName'])
		{
			$_GET['id'] = safe($_GET['id']);
			$_POST['LastName'] = safe($_POST['LastName']);
			$_POST['FirstName'] = safe($_POST['FirstName']);
			$_POST['MiddleName'] = safe($_POST['MiddleName']);
			mysql_query("UPDATE doctor SET LastName='$_POST[LastName]', FirstName='$_POST[FirstName]', MiddleName='$_POST[MiddleName]' WHERE id=$_GET[id]");
		}
	}
	elseif (isset($_POST['delete']))
	{
		$_GET['id'] = safe($_GET['id']);
		mysql_query("DELETE FROM doctor WHERE id=$_GET[id]");
	}
	elseif (isset($_POST['to_archive']))
	{
		$_GET['id'] = safe($_GET['id']);

		$result = mysql_query("SELECT id FROM patient WHERE DocId=$_GET[id]");
		while ($row = mysql_fetch_array($result))
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
	}
	elseif (isset($_POST['from_archive']))
	{
		$_GET['id'] = safe($_GET['id']);

		$result = mysql_query("SELECT id FROM archive WHERE DocId=$_GET[id]");
		while ($row = mysql_fetch_array($result))
		{
			mysql_query("INSERT INTO patient (id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
												PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId)
								SELECT id, LastName, FirstName, MiddleName, BirthDay, BirthMonth, BirthYear,
												PhoneHome, PhoneWork, PhoneMobile, AddrHome, WorkPlace, Sex, DocId
								FROM archive WHERE id=$row[id]");

			mysql_query("DELETE FROM archive WHERE id=$row[id]");
		
			mysql_query("UPDATE reminder
						SET visit_day=0,
							visit_month=0,
							visit_year=0,
							visit_miss=0,
							visit_your_own=0,
							visit_done=0
						WHERE PatId=$row[id]");
		}
	}
?>