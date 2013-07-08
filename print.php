<?php
if (isset($_POST['print_visit']))
{
    $page = "visit";
}
elseif (isset($_POST['print_birthday']))
{
    $page = "birthday";
}
elseif (isset($_POST['print_patient']))
{
    $page = "doctor_wishes";
}
else
{
	$path = $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://$path/index.php");
	exit;
}

include ("connect.php");
include ("functions.php");

?>
<center>
<table cellpadding="0" cellspacing="0" border="1">
<?php
if ($page == "visit")
{
?>
<tr><td colspan="8" style="text-align: center; width: 1000px;"><b>Перелік пацієнтів, яким потрібно нагадати про контрольний огляд</b></td></tr>
<tr>
    <td style="text-align: center;">П.І.П.</td>
    <td style="width: 80px; text-align: center;">День народження</td>
    <td style="width: 80px; text-align: center;">Дата останнього візиту</td>
    <td style="text-align: center;">Тел. роб.</td>
    <td style="text-align: center;">Тел. дом.</td>
    <td style="text-align: center;">Тел. моб.</td>
    <td style="text-align: center;">Лікар</td>
    <td style="text-align: center;">Коментар</td>
</tr>
<?php
    $jCount = 0;
	foreach($_POST as $key => $value){
		if (is_numeric($key)){
            $result = mysql_query("SELECT * FROM patient WHERE id='$key'");
            if ($row = mysql_fetch_array($result)){
                if ($row['PhoneWork'] == ""){ $row['PhoneWork'] = "&nbsp"; }
                if ($row['PhoneHome'] == ""){ $row['PhoneHome'] = "&nbsp"; }
                if ($row['PhoneMobile'] == ""){ $row['PhoneMobile'] = "&nbsp"; }
                $lastVisitInfo = mysql_query("SELECT * FROM reception WHERE PatId='$key' ORDER BY year DESC, month DESC, day DESC LIMIT 1");
                $lastVisitRow = mysql_fetch_array($lastVisitInfo);
                $doctorInfo = mysql_query("SELECT * FROM doctor WHERE id=$row[DocId]");
                $doctorRow = mysql_fetch_array($doctorInfo);
                if ($row['DocId'] == 0){
                    $doctorRow['LastName'] = "&nbsp";
                    $doctorRow['FirstName'] = "&nbsp";
                    $doctorRow['MiddleName'] = "&nbsp";
                }
                $RemInfo = mysql_query("SELECT * FROM reminder WHERE PatId='$key'");
                $RemRow = mysql_fetch_array($RemInfo);
				if ($RemRow['visit_miss'] > 0)
				{
					$comment = "П(".$RemRow['visit_miss'].") ";
				}
				else
				{
					$comment = "";
				}

				if ($RemRow['visit_your_own'] == 1)
				{
					$comment .= "С ";
				}
				
				if ($RemRow['not_phoned'] == 1)
				{
					$comment .= "Н";
				}
				else
				{
					$comment .= "&nbsp;";
				}
				
                echo "<tr>
  <td>".(++$jCount).". ".cleanup($row['LastName'])." ".cleanup($row['FirstName'])." ".cleanup($row['MiddleName'])."</td>
  <td style='text-align: center'>$row[BirthDay].".($row['BirthMonth']<10?'0':'')."$row[BirthMonth].$row[BirthYear]</td>
  <td style='text-align: center'>$lastVisitRow[day].".($lastVisitRow['month']<10?'0':'')."$lastVisitRow[month].$lastVisitRow[year]</td>
  <td style='text-align: center'>$row[PhoneWork]</td>
  <td style='text-align: center'>$row[PhoneHome]</td>
  <td style='text-align: center'>$row[PhoneMobile]</td>
  <td>".cleanup($doctorRow['LastName'])." ".cleanup($doctorRow['FirstName'])." ".cleanup($doctorRow['MiddleName'])."</td>
  <td>$comment</td>
</tr>\n";
                mysql_query("UPDATE reminder SET visit_done=1 WHERE PatId=$key");
            }
        }
	}
}
elseif ($page == "birthday")
{
?>
<tr><td colspan="5" style="text-align: center; width: 1000px;"><b>Перелік пацієнтів, яких потрібно привітати з Днем народження</b></td></tr>
<tr>
    <td style="text-align: center;">П.І.П.</td>
    <td style="width: 100px; text-align: center;">День народження</td>
    <td style="width: 350px; text-align: center;">Адреса</td>
    <td style="text-align: center;">Лікар</td>
    <td style="width: 50px; text-align: center;">Коментар</td>
</tr>
<?php
    $jCount = 0;
	foreach($_POST as $key => $value)
	{
		if (is_numeric($key))
		{
            $result = mysql_query("SELECT * FROM patient WHERE id=$key ORDER BY BirthMonth, BirthDay, BirthYear");
            if ($row = mysql_fetch_array($result))
			{
                if($row['LastName'] == ""){ $row['LastName'] = "&nbsp;"; }
                if($row['FirstName'] == ""){ $row['FirstName'] = "&nbsp;"; }
                if($row['MiddleName'] == ""){ $row['MiddleName'] = "&nbsp;"; }
//                if($row['BirthDay'] == 0){ $row['BirthDay'] = "&nbsp;"; }
//                if($row['BirthMonth'] == 0){ $row['BirthMonth'] = "&nbsp;"; }
//                if($row['BirthYear'] == 0){ $row['BirthYear'] = "&nbsp;"; }
//                if($row['AddrHome'] == ""){ $row['AddrHome'] = "&nbsp;"; }
                
                $doctorInfo = mysql_query("SELECT * FROM doctor WHERE id=$row[DocId]");
                $doctorRow = mysql_fetch_array($doctorInfo);
                if ($row['DocId'] == 0)
				{
                    $doctorRow['LastName'] = "&nbsp";
                    $doctorRow['FirstName'] = "&nbsp";
                    $doctorRow['MiddleName'] = "&nbsp";
                }
                
                echo "
<tr>
  <td>".(++$jCount).". ".cleanup($row['LastName'])." ".cleanup($row['FirstName'])." ".cleanup($row['MiddleName'])."</td>
  <td style='text-align: center'>$row[BirthDay].".($row['BirthMonth']<10?'0':'')."$row[BirthMonth].$row[BirthYear]</td>
  <td>".cleanup($row['AddrHome'])."</td>
  <td>".cleanup($doctorRow['LastName'])." ".cleanup($doctorRow['FirstName'])." ".cleanup($doctorRow['MiddleName'])."</td>
  <td>&nbsp;</td>
</tr>\n";
                mysql_query("UPDATE reminder SET birthday_done=1 WHERE PatId=$key");
            }
        }
	}
}
elseif ($page == "doctor_wishes")
{
	$_GET['id'] = safe($_GET['id']);
	$doctorInfo = mysql_query("SELECT * FROM doctor WHERE id=$_GET[id]");
	$doctorRow = mysql_fetch_array($doctorInfo);
?>
<tr><td colspan="4" style="text-align: center; width: 1000px;"><b>Перелік пацієнтів <?php echo $doctorRow['LastName'] . " " .
																							   $doctorRow['FirstName'] . " " .
																							   $doctorRow['MiddleName']; ?></b></td></tr>
<tr>
    <td style="text-align: center;">П.І.П.</td>
    <td style="width: 100px; text-align: center;">День народження</td>
    <td style="width: 350px; text-align: center;">Адреса</td>
    <td style="width: 50px; text-align: center;">Коментар</td>
</tr>
<?php
    $jCount = 0;
	$result = mysql_query("SELECT * FROM patient WHERE DocId=$_GET[id] ORDER BY LastName, FirstName, MiddleName");
	while ($row = mysql_fetch_array($result))
	{
		$isInBlackList = mysql_query("SELECT COUNT(*) FROM blacklist WHERE PatId=$row[id]");
		$isInBlackListRow = mysql_fetch_array($isInBlackList);
		if ($isInBlackListRow[0] != 0)
		{
			continue;
		}

		if($row['LastName'] == ""){ $row['LastName'] = "&nbsp;"; }
		if($row['FirstName'] == ""){ $row['FirstName'] = "&nbsp;"; }
		if($row['MiddleName'] == ""){ $row['MiddleName'] = "&nbsp;"; }
		if($row['AddrHome'] == ""){ $row['AddrHome'] = "&nbsp;"; }

		echo "
<tr>
  <td>".(++$jCount).". ".cleanup($row['LastName'])." ".cleanup($row['FirstName'])." ".cleanup($row['MiddleName'])."</td>
  <td style='text-align: center'>$row[BirthDay].".($row['BirthMonth']<10?'0':'')."$row[BirthMonth].$row[BirthYear]</td>
  <td>".cleanup($row['AddrHome'])."</td>
  <td>&nbsp;</td>
</tr>\n";
	}
}
?>
</table>
</center>