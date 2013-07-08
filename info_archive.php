<?php
if (isset($_GET['add']))
{
	$row['LastName'] = "";
	$row['FirstName'] = "";
	$row['MiddleName'] = "";
	$row['Sex'] = "";

	$row['BirthdayReminder'] = 0;

	$row['BirthDay'] = 0;
	$row['BirthMonth'] = 0;
	$row['BirthYear'] = 0;
	$row['PhoneWork'] = "";
	$row['PhoneHome'] = "";
	$row['PhoneMobile'] = "";
	$row['AddrHome'] = "";
	$row['WorkPlace'] = "";
	$row['DocId'] = 0;

	$row['VisitDay'] = 0;
	$row['VisitMonth'] = 0;
	$row['VisitYear'] = 0;
	$row['VisitMiss'] = 0;
	$row['VisitRemind'] = 0;

	$row['Description'] = "";
}
else
{
	$result = mysql_query("SELECT * FROM archive WHERE id=$_GET[id]");
	$row = mysql_fetch_array($result);
	foreach ($row as $key => $value)
	{
    	$row[$key] = cleanup($value);
	}
	unset($key, $value);
	
	$result = mysql_query("SELECT Description FROM blacklist WHERE PatId=$_GET[id]");
	$rowBL = mysql_fetch_array($result);
	$row['Description'] = cleanup($rowBL['Description']);

	$result = mysql_query("SELECT * FROM reminder WHERE PatId=$_GET[id]");
	$rowRem = mysql_fetch_array($result);
	foreach ($rowRem as $key => $value)
	{
		$rowRem[$key] = cleanup($value);
	}
	unset($key, $value);

	$row['BirthdayReminder'] = $rowRem['birthday_none'];
	$row['VisitDay'] = $rowRem['visit_day'];
	$row['VisitMonth'] = $rowRem['visit_month'];
	$row['VisitYear'] = $rowRem['visit_year'];
	$row['VisitMiss'] = $rowRem['visit_miss'];
	$row['VisitRemind'] = $rowRem['visit_your_own'];
}

$url = "?page=$_SESSION[page]&id=" . (isset($_GET['id']) ? $_GET['id'] : "") . "&search=$_SESSION[search]&val=$_SESSION[val]";
?>

<form method="post" action="<?php echo $url; ?>" onsubmit="
if (getElementById('LastName').value == '' &&
	getElementById('FirstName').value == '' &&
    getElementById('MiddleName').value == '')
{
    alert('Введіть інформацію про пацієнта!');
    return false;
}
else
{
	return confirm('Продовжити операцію?');
}">
<div class="UserInfo">
<div class="Line1_1" style="top:5px">Прізвище</div>
<div class="Line1_2" style="top:5px"><input type="text" id="LastName" name="LastName" maxlength="30" class="InputLine0" value="<?php echo $row['LastName']; ?>"></div>

<div class="Line2_1" style="top:30px">Ім'я</div>
<div class="Line2_2" style="top:30px"><input type="text" id="FirstName" name="FirstName" maxlength="30" class="InputLine0" value="<?php echo $row['FirstName']; ?>"></div>

<div class="Line1_1" style="top:55px">По-батькові</div>
<div class="Line1_2" style="top:55px"><input type="text" id="MiddleName" name="MiddleName" maxlength="30" class="InputLine0" value="<?php echo $row['MiddleName']; ?>"></div>

<div class="Line2_1" style="top:80px">Стать</div>
<div class="Line2_2" style="top:80px">
<select name="Sex" class="InputLine0" style="width:275px">
<?php
if ($row['Sex'] == "")
	echo "<option value=\"\" selected=\"selected\"></option>
	<option value=\"ч\">Чоловіча</option>
	<option value=\"ж\">Жіноча</option>";

elseif ($row['Sex'] == "ч")
	echo "<option value=\"\"></option>
  	<option value=\"ч\" selected=\"selected\">Чоловіча</option>
	<option value=\"ж\">Жіноча</option>";

elseif ($row['Sex'] == "ж")
	echo "<option value=\"\"></option>
  	<option value=\"ч\">Чоловіча</option>
	<option value=\"ж\" selected=\"selected\">Жіноча</option>";
?>
</select>
</div>

<div class="Line1_1" style="top:105px">День народження <input type="checkbox" name="BirthdayReminder" <?php if ($row['BirthdayReminder'] == 1) echo "checked=\"checked\""; ?> style="position:absolute; top:6px; left:140px" disabled="disabled" /></div>
<div class="Line1_2" style="top:105px">
<select name="BirthDay" class="SelectInputLine" style="left:1px; width:45px">
	<option value="0" <?php echo ($row['BirthDay'] == 0 ? "selected=\"selected\"" : "" ); ?>></option>
<?php for ($iDay = 1; $iDay <= 31; $iDay++) echo "\t<option value=\"$iDay\"". ($iDay == $row['BirthDay'] ? "selected=\"selected\"" : "") .">$iDay</option>\n"; ?></select>

<select name="BirthMonth" class="SelectInputLine" style="left:51px; width:45px">
	<option value="0" <?php echo ($row['BirthMonth'] == 0 ? "selected=\"selected\"" : " " ); ?>></option>
<?php for ($iMonth = 1; $iMonth <= 12; $iMonth++) echo "\t<option value=\"$iMonth\"". ($iMonth == $row['BirthMonth'] ? "selected=\"selected\"" : " ") .">$iMonth</option>\n"; ?></select>

<select name="BirthYear" class="SelectInputLine" style="left:101px; width:60px">
	<option value="0" <?php echo ($row['BirthYear'] == 0 ? "selected=\"selected\"" : " " ); ?>></option>
<?php for ($iYear = 1900; $iYear <= $year; $iYear++) echo "\t<option value=\"$iYear\"". ($iYear == $row['BirthYear'] ? "selected=\"selected\"" : " ") .">$iYear</option>\n"; ?></select>
</div>

<div class="Line2_1" style="top:130px">Тел. робочий</div>
<div class="Line2_2" style="top:130px"><input type="text" name="PhoneWork" maxlength="30" class="InputLine0" value="<?php echo $row['PhoneWork']; ?>"></div>

<div class="Line1_1" style="top:155px">Тел. домашній</div>
<div class="Line1_2" style="top:155px"><input type="text" name="PhoneHome" maxlength="30" class="InputLine0" value="<?php echo $row['PhoneHome']; ?>"></div>

<div class="Line2_1" style="top:180px">Тел. мобільний</div>
<div class="Line2_2" style="top:180px"><input type="text" name="PhoneMobile" maxlength="30" class="InputLine0" value="<?php echo $row['PhoneMobile']; ?>"></div>

<div class="Line1_1" style="top:205px; height:74px">Адреса</div>
<div class="Line1_2" style="top:205px; height:80px"><textarea name="AddrHome" dir="ltr"><?php echo $row['AddrHome']; ?></textarea></div>

<div class="Line2_1" style="top:285px; height:74px">Місце праці</div>
<div class="Line2_2" style="top:285px; height:80px"><textarea name="WorkPlace" dir="ltr"><?php echo $row['WorkPlace']; ?></textarea></div>

<div class="Line1_1" style="top:365px">Лікар</div>
<div class="Line1_2" style="top:365px">
<select name="DocId" class="InputLine0" style="width:275px">
	<option value="0" <?php echo ($row['DocId'] == 0 ? "selected=\"selected\"" : " "); ?>></option>
<?php 
	$result = mysql_query("SELECT * FROM doctor ORDER BY LastName, FirstName, MiddleName");
	while ( $rowDoc = mysql_fetch_array($result) )
	{
		foreach ($rowDoc as $key => $value)
		{
    		$rowDoc[$key] = cleanup($value);
		}
		echo "\t<option value=\"$rowDoc[id]\"". ($rowDoc['id'] == $row['DocId'] ? "selected=\"selected\"" : "") .">$rowDoc[LastName] $rowDoc[FirstName] $rowDoc[MiddleName]</option>\n";
	}
	unset($key, $value);
?>
</select></div>

<div class="Line2_1" style="top:390px">Наступний візит</div>
<div class="Line2_2" style="top:390px">
<select name="VisitDay" class="SelectInputLine" style="left:1px; width:45px" disabled="disabled">
	<option value="0" <?php echo ($row['VisitDay'] == 0 ? "selected=\"selected\"" : "" ); ?>></option>
<?php for ($iDay = 1; $iDay <= 31; $iDay++) echo "\t<option value=\"$iDay\"". ($iDay == $row['VisitDay'] ? "selected=\"selected\"" : "") .">$iDay</option>\n"; ?></select>

<select name="VisitMonth" class="SelectInputLine" style="left:51px; width:45px" disabled="disabled">
	<option value="0" <?php echo ($row['VisitMonth'] == 0 ? "selected=\"selected\"" : "" ); ?>></option>
<?php for ($iMonth = 1; $iMonth <= 12; $iMonth++) echo "\t<option value=\"$iMonth\"". ($iMonth == $row['VisitMonth'] ? "selected=\"selected\"" : " ") .">$iMonth</option>\n"; ?></select>

<select name="VisitYear" class="SelectInputLine" style="left:101px; width:60px" disabled="disabled">
	<option value="0" <?php echo ($row['VisitYear'] == 0 ? "selected=\"selected\"" : "" ); ?>></option>
<?php for ($iYear = $year; $iYear <= $year + 10; $iYear++) echo "\t<option value=\"$iYear\"". ($iYear == $row['VisitYear'] ? "selected=\"selected\"" : " ") .">$iYear</option>\n"; ?></select>
</div>
<div class="Line1_1" style="top:415px"></div>
<div class="Line1_2" style="top:415px"></div>

<div class="Line2_1" style="top:440px">
<input type="submit" name="archive" value="Повернути з архіву" <?php if (isset($_GET['add'])) echo "disabled=\"disabled\" style=\"visibility:hidden\""; else echo "style=\"position:absolute; top:1px; left:5px;\""; ?> />
</div>
<div class="Line2_2" style="top:440px; text-align:right">
<input type="submit" name="delete" value="Видалити" <?php if (isset($_GET['add'])) echo "disabled=\"disabled\" style=\"visibility:hidden\""; ?> />
&nbsp;&nbsp;
<input type="submit" <?php if (isset($_GET['add']))	echo "name=\"add\" value=\"Додати\""; else echo "name=\"save\" value=\"Зберегти\""; ?> />
&nbsp;</div>

</div>
</form>

<!-- ============================================================================================================================================== -->
<?php if (!isset($_GET['add'])) { ?>
<div class="ReceptionInfo">
<div class="Title" style="left:5px; width:110px">Дата прийому</div>
<div class="Title" style="left:130px; width:220px">Лікар</div>
<div class="Title" style="left:365px; width:95px"></div>
<div class="Table">
<table cellpadding="0" cellspacing="0">
<?php
$result = mysql_query("SELECT * FROM reception WHERE PatId=$_GET[id] ORDER BY year, month, day");
while ($rowRec = mysql_fetch_array($result))
{
	foreach ($rowRec as $key => $value)
	{
		$rowRec[$key] = cleanup($value);
	}
	unset($key, $value);
	
	$res = mysql_query("SELECT * FROM doctor WHERE id=$rowRec[DocId]");
	if ($rowDoc = mysql_fetch_array($res))
	{
		foreach ($rowDoc as $key => $value)
		{
			$rowDoc[$key] = cleanup($value);
		}
		unset($key, $value);
	}
	else
	{
		$rowDoc['LastName'] = "";
		$rowDoc['FirstName'] = "";
		$rowDoc['MiddleName'] = "";
	}
	
	echo "<tr>
			<td class=\"Column0\">" . $rowRec['day'] . "." . ($rowRec['month'] < 10 ? "0" : "") . $rowRec['month'] . "." . $rowRec['year'] . "</td>
			<td class=\"Column1\">" . $rowDoc['LastName'] . " " . $rowDoc['FirstName'] . " " . $rowDoc['MiddleName'] . "</td>
			<td class=\"Column2\"><form method=\"post\" action=\"$url\" onsubmit=\"return confirm('Продовжити операцію?');\"><input type=\"text\" name=\"ReceptionId\" value=\"$rowRec[id]\" style=\"position:absolute; top:0px; left:0px; width:0px; height:0px; visibility:hidden\" /><input type=\"submit\" name=\"delete_reception\" value=\"Відмінити\" /></form></td>
		</tr>";
}
?>
</table>
</div>
<form method="post" action="<?php echo $url; ?>" onsubmit="return confirm('Продовжити операцію?');">
<div class="BottomPanel">
<select name="ReceptionDay" class="SelectInputLine" style="left:3px; width:45px">
<?php for ($iDay = 1; $iDay <= 31; $iDay++) echo "\t<option value=\"$iDay\"". ($iDay == $day ? "selected=\"selected\"" : "") .">$iDay</option>\n"; ?></select>
<select name="ReceptionMonth" class="SelectInputLine" style="left:53px; width:45px">
<?php for ($iMonth = 1; $iMonth <= 12; $iMonth++) echo "\t<option value=\"$iMonth\"". ($iMonth == $month ? "selected=\"selected\"" : "") .">$iMonth</option>\n"; ?></select>
<select name="ReceptionYear" class="SelectInputLine" style="left:103px; width:60px">
<?php for ($iYear = 2000; $iYear <= $year; $iYear++) echo "\t<option value=\"$iYear\"". ($iYear == $year ? "selected=\"selected\"" : "") .">$iYear</option>\n"; ?></select>
<select name="ReceptionDoctor" class="SelectInputLine" style="left:168px; width:230px">
	<option value="0" <?php echo ($row['DocId'] == 0 ? "selected=\"selected\"" : ""); ?>></option>
<?php 
	$result = mysql_query("SELECT * FROM doctor ORDER BY LastName, FirstName, MiddleName");
	while ( $rowDoc = mysql_fetch_array($result) )
	{
		foreach ($rowDoc as $key => $value)
		{
    		$rowDoc[$key] = cleanup($value);
		}
		echo "\t<option value=\"$rowDoc[id]\"". ($rowDoc['id'] == $row['DocId'] ? "selected=\"selected\"" : " ") .">$rowDoc[LastName] $rowDoc[FirstName] $rowDoc[MiddleName]</option>\n";
	}
	unset($key, $value);
?>	                                        
</select>
<input type="submit" name="add_reception" value="Додати" disabled="disabled" style="position:absolute; top:1px; left:400px" />
</div>
</form>
</div>

<div class="BlackListInfo">
<form method="post" action="<?php echo $url; ?>" onsubmit="
if (getElementById('Description').value == '')
{
    alert('Введіть причину!');
    return false;
}
else
{
	return confirm('Продовжити операцію?');
}">
<div class="Title" style="left:5px; width:455px">Чорний список</div>
<textarea dir="ltr" id="Description" name="Description" class="Text"><?php echo $row['Description']; ?></textarea>
<div class="BottomPanel"><input type="submit" name="delete_blacklist" value="Видалити" <?php if ($row['Description'] == "") echo "disabled=\"disabled\" style=\"visibility:hidden\""; ?> />&nbsp;&nbsp;&nbsp;<input type="submit" <?php
if ($row['Description'] != "")
	echo "name=\"save_blacklist\" value=\"Зберегти\"";
else
	echo "name=\"add_blacklist\" value=\"Додати\""; ?> />
</div>
</form>
</div>
<?php 
	if ($row['Description'] != "")
	{
		echo "<div style=\"position:absolute;top:5px; left:140px; color:#F00; font-size:36px\">*</div>";
	}
}
?>