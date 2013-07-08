<?php
if (isset($_GET['add']))
{
	$row['LastName'] = "";
	$row['FirstName'] = "";
	$row['MiddleName'] = "";
}
else
{
	$result = mysql_query("SELECT * FROM doctor WHERE id = $_GET[id]");
	$row = mysql_fetch_array($result);

	$row['LastName'] = cleanup($row['LastName']);
	$row['FirstName'] = cleanup($row['FirstName']);
	$row['MiddleName'] = cleanup($row['MiddleName']);
}

$url = "?page=$_SESSION[page]&id=" . (isset($_GET['id']) ? $_GET['id'] : "") . "&search=$_SESSION[search]&val=$_SESSION[val]";
?>

<form method="post" action="<?php echo $url; ?>" onsubmit="
if (getElementById('LastName').value == '' &&
	getElementById('FirstName').value == '' &&
    getElementById('MiddleName').value == '')
{
    alert('Введіть інформацію про лікаря!');
    return false;
}
else
{
	return confirm('Продовжити операцію?');
}">
<div class="UserInfo" style="height:110px">
<div class="Line1_1" style="top:5px">Прізвище</div>
<div class="Line1_2" style="top:5px"><input type="text" id="LastName" name="LastName" maxlength="30" class="InputLine0" value="<?php echo $row['LastName']; ?>"></div>

<div class="Line2_1" style="top:30px">Ім'я</div>
<div class="Line2_2" style="top:30px"><input type="text" id="FirstName" name="FirstName" maxlength="30" class="InputLine0" value="<?php echo $row['FirstName']; ?>"></div>

<div class="Line1_1" style="top:55px">По-батькові</div>
<div class="Line1_2" style="top:55px"><input type="text" id="MiddleName" name="MiddleName" maxlength="30" class="InputLine0" value="<?php echo $row['MiddleName']; ?>"></div>

<div class="Line2_1" style="top:80px">
<input type="submit" name="to_archive" style="position:absolute; top:1px; left:1px" value="<" title="В старий архів" <?php echo (isset($_GET['add'])? "disabled=\"disabled\"":""); ?> />
<input type="submit" name="from_archive" style="position:absolute; top:1px; left:25px" value=">" title="Повернути з архіву" <?php echo (isset($_GET['add'])? "disabled=\"disabled\"":""); ?> />
</div>
<div class="Line2_2" style="top:80px; text-align:right">
<input type="submit" name="delete" value="Видалити" <?php if (isset($_GET['add'])) echo "disabled=\"disabled\" style=\"visibility:hidden\""; ?> />
&nbsp; &nbsp;
<input type="submit" <?php if (isset($_GET['add']))	echo "name=\"add\" value=\"Додати\""; else echo "name=\"save\" value=\"Зберегти\""; ?> />
&nbsp;</div>

</div>
</form>
<form method="post" action="print.php?id=<?php echo $_GET['id']; ?>" onsubmit="return confirm('Продовжити операцію?');">
	<input type="submit" name="print_patient" value="Список пацієнтів" style="position:absolute; top:86px; left:70px;" <?php echo (isset($_GET['add'])? "disabled=\"disabled\"":""); ?> />
</form>