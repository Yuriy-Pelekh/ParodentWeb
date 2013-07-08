<form class="Search">
	<input class="Input" id="input" type="text" value="<?php echo $_SESSION['search']; ?>" maxlength="50" onkeyup="userList(this.value, val)" /><br />
    <input id="where" type="radio" name="where" value="0" <?php if (!$_SESSION['val']) echo "checked=\"checked\""; ?> onclick="val=this.value; userList(document.getElementById('input').value, val)" /> по прізвищу&nbsp;&nbsp;&nbsp;
    <input id="where" type="radio" name="where" value="1" <?php if ($_SESSION['val']) echo "checked=\"checked\""; ?>  onclick="val=this.value; userList(document.getElementById('input').value, val)" /> по імені
</form>
<div class="List" id="list">&nbsp;&nbsp;&nbsp;Завантаження...</div>