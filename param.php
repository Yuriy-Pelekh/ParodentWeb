<?php
	if (isset($_POST['update']))
	{
		mysql_query("UPDATE param SET visit_day=$_POST[visit], birth_day=$_POST[birth]");
	}

	$arr = array("1-ша половина попереднього місяця", "2-га половина попереднього місяця",
				 "1-ша половина поточного місяця", "2-га половина поточного місяця",
				 "1-ша половина наступного місяця", "2-га половина наступного місяця");
?>
<div style="position:absolute; top:0px; left:5px; width:240px; height:140px; background-color:#EEE">
<form method="post" action="?page=reminder">
<div style="position:absolute; top:5px; left:5px; width:230px; height:20px; text-align:center; background-color:#CCC">Контрольний огляд</div>
<select name="visit" style="position:absolute; top:27px; left:5px; width:230px">
<?php
$result = mysql_query("SELECT * FROM param");
$row = mysql_fetch_array($result);
foreach ($arr as $key => $value) echo "\t<option value=\"$key\" " . ($key == $row['visit_day'] ? "selected=\"selected\"" : "") . ">$value</option>\n";
unset($key, $value);
?>
</select>
<div style="position:absolute; top:55px; left:5px; width:230px; height:20px; text-align:center; background-color:#CCC">День народження</div>
<select name="birth" style="position:absolute; top:77px; left:5px; width:230px">
<?php
foreach ($arr as $key => $value) echo "\t<option value=\"$key\" " . ($key == $row['birth_day'] ? "selected=\"selected\"" : "") . ">$value</option>\n";
unset($key, $value);
?>
</select>
<input type="submit" name="update" value="Оновити" style="position:absolute; top:105px; left:5px; width:230px; height:30px" />
</form>
</div>
