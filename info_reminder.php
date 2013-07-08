<script language="javascript">
function is_checked(id, table){
    if (document.getElementById(id).checked){
        checked = "0";
    } else {
        checked = "1";
    }
    changeState(id, checked, table);
}
</script>
<?php
	$rV = $row['visit_day'];
	$rB = $row['birth_day'];
	
	switch ($rV)
	{
		case 0: $rVD1 = 1;  $rVD2 = 15; $rVM = $month - 1; $rVY = $year; break;
		case 1: $rVD1 = 16; $rVD2 = 31; $rVM = $month - 1; $rVY = $year; break;
		case 2: $rVD1 = 1;  $rVD2 = 15; $rVM = $month;     $rVY = $year; break;
		case 3: $rVD1 = 16; $rVD2 = 31; $rVM = $month;     $rVY = $year; break;
		case 4: $rVD1 = 1;  $rVD2 = 15; $rVM = $month + 1; $rVY = $year; break;
		case 5: $rVD1 = 16; $rVD2 = 31; $rVM = $month + 1; $rVY = $year; break;
	}
	
	if ($rVM == 0)
	{
		$rVM = 12;
		$rVY --;	
	}
	if ($rVM == 13)
	{
		$rVM = 1;
		$rVY ++;
	}
	
	switch ($rB)
	{
		case 0: $rBD1 = 1;  $rBD2 = 15; $rBM = $month - 1; break;
		case 1: $rBD1 = 16; $rBD2 = 31; $rBM = $month - 1; break;
		case 2: $rBD1 = 1;  $rBD2 = 15; $rBM = $month;     break;
		case 3: $rBD1 = 16; $rBD2 = 31; $rBM = $month;     break;
		case 4: $rBD1 = 1;  $rBD2 = 15; $rBM = $month + 1; break;
		case 5: $rBD1 = 16; $rBD2 = 31; $rBM = $month + 1; break;
	}
	
	if ($rBM == 0)  $rBM = 12;
	if ($rBM == 13) $rBM = 1;

	$color = array("EEE", "FEE");
?>
<form method="post" action="print.php" onsubmit="return confirm ('Продовжити операцію?')">
<div style="position:absolute; top:5px; left:5px; width:460px; height:580px; background-color:#EEE">

<div style="position:absolute; top:5px; left:5px; width:450px; height:25px; background-color:#CCC; text-align:center; font-weight:bold; padding-top:5px">Нагадування про контрольний огляд</div>

<div style="position:absolute; top:40px; left:5px; width:450px; height:500px; background-color:#FFF; overflow:auto">
<table cellpadding="1" cellspacing="1" style="width:100%">
<?php
	$nCount = 0;
	$result = mysql_query("SELECT * FROM reminder, patient
							WHERE reminder.visit_month=$rVM AND
								  reminder.visit_day>=$rVD1 AND
								  reminder.visit_day<=$rVD2 AND
								  reminder.visit_year=$rVY AND
								  reminder.PatId=patient.id AND
								  (patient.PhoneHome!='' OR
								  patient.PhoneWork!='' OR
								  patient.PhoneMobile!='') 
							ORDER BY reminder.visit_year, reminder.visit_month, reminder.visit_day");
	while ($row = mysql_fetch_array($result))
	{	
		echo "
<tr style=\"background-color:#" . ($nCount++ % 2 == 0 ? $color[0] : $color[1]) . "\">
	<td style=\"width:18px; text-align:center\"><input id=\"$row[id]\" name=\"$row[id]\" type=\"checkbox\"" . ($row['visit_done'] == 0 ? "checked=\"checked\"" : "") . " onclick='is_checked(this.id, \"visit_done\")' /></td>
	<td>" . cleanup($row['LastName']) . " " . cleanup($row['FirstName']) . " " . cleanup($row['MiddleName']) . "&nbsp;</td>
	<td style=\"width:115px; text-align:center\">" . $row['visit_day'] . "." . ($row['visit_month']<10?"0":"") . $row['visit_month'] . "." . $row['visit_year'] . "</td>
</tr>";
	}
?>
</table>
</div>

<div style="position:absolute; top:545px; left:5px; width:450px; height:25px; background-color:#CCC; text-align:right; font-weight:bold; padding-top:5px">(<?php echo $nCount; ?>) <input type="submit" name="print_visit" value="Версія для друку" />&nbsp;</div>
</div>
</form>
<!-- ================================================================================================================================== -->
<form method="post" action="print.php" onsubmit="return confirm ('Продовжити операцію?')">
<div style="position:absolute; top:5px; left:475px; width:460px; height:580px; background-color:#EEE">

<div style="position:absolute; top:5px; left:5px; width:450px; height:25px; background-color:#CCC; text-align:center; font-weight:bold; padding-top:5px">Нагадування про день народження</div>

<div style="position:absolute; top:40px; left:5px; width:450px; height:500px; background-color:#FFF; overflow:auto">
<table cellpadding="1" cellspacing="1" style="width:100%">
<?php
	$nCount = 0;
	$result = mysql_query("SELECT * FROM patient, reminder
							WHERE patient.BirthMonth=$rBM AND
								  patient.BirthDay>=$rBD1 AND
								  patient.BirthDay<=$rBD2 AND
								  patient.AddrHome!='' AND
								  reminder.birthday_none=0 AND
								  patient.id=reminder.PatId
							ORDER BY patient.BirthMonth, patient.BirthDay, patient.BirthYear");
	while ($row = mysql_fetch_array($result))
	{	
		echo "
<tr style=\"background-color:#" . ($nCount++ % 2 == 0 ? $color[0] : $color[1]) . "\">
	<td style=\"width:18px; text-align:center\"><input id=\"$row[id]\" name=\"$row[id]\" type=\"checkbox\"" . ($row['birthday_done'] == 0 ? "checked=\"checked\"" : "") . " onclick='is_checked(this.id, \"birthday_done\")' /></td>
	<td>" . cleanup($row['LastName']) . " " . cleanup($row['FirstName']) . " " . cleanup($row['MiddleName']) . "&nbsp;</td>
	<td style=\"width:115px; text-align:center\">" . $row['BirthDay'] . "." . ($row['BirthMonth']<10?"0":"") . $row['BirthMonth'] . "." . $row['BirthYear'] . "</td>
</tr>";
	}
?>
</table>
</div>

<div style="position:absolute; top:545px; left:5px; width:450px; height:25px; background-color:#CCC; text-align:right; font-weight:bold; padding-top:5px">(<?php echo $nCount; ?>) <input type="submit" name="print_birthday" value="Версія для друку" />&nbsp;</div>
</div>
</form>