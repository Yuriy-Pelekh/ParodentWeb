&nbsp;Parodent

<?php if ($_SESSION['page'] == "patient" || $_SESSION['page'] == "doctor") { ?><div class="Add"><a href="?page=<?php echo $_SESSION['page']; ?>&add=1" target="_self">Додати <?php if ($_SESSION['page'] == "patient") echo "пацієнта"; elseif ($_SESSION['page'] == "doctor") echo "лікаря"; ?></a></div><?php } ?>

<!-- Menu --><div class="Menu">
<a href="?page=archive" target="_self" <?php if ($_SESSION['page'] == "archive") echo "style=\"font-weight:bold\""; ?>>Старий архів</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?page=reminder" target="_self" <?php if ($_SESSION['page'] == "reminder") echo "style=\"font-weight:bold\""; ?>>Нагадування</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?page=blacklist" target="_self" <?php if ($_SESSION['page'] == "blacklist") echo "style=\"font-weight:bold\""; ?>>Чорний список</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?page=doctor" target="_self" <?php if ($_SESSION['page'] == "doctor") echo "style=\"font-weight:bold\""; ?>>Лікарі</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?page=patient" target="_self" <?php if ($_SESSION['page'] == "patient") echo "style=\"font-weight:bold\""; ?>>Пацієнти</a>
&nbsp;&nbsp;
</div><!-- /Menu -->
<div class="Info"><img src="info.png" title="&copy; Юрій Пелех
(093)-108-04-04" /></div>