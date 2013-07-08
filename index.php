<!-- <?php
session_start();

include("connect.php");
include("functions.php");

if (isset($_GET['page']))
{
	$_SESSION['page'] = $_GET['page'];
}
elseif (!isset($_SESSION['page']))
{
	$_SESSION['page'] = "patient";
}

if (isset($_GET['search']))
{
	$_SESSION['search'] = $_GET['search'];
}
elseif (!isset($_SESSION['search']))
{
	$_SESSION['search'] = "";
}

if (isset($_GET['val']))
{
	$_SESSION['val'] = $_GET['val'];
}
elseif (!isset($_SESSION['val']))
{
	$_SESSION['val'] = 0;
}

list($day, $month, $year) = explode(" ", date("j n Y"));
?> -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Yurij Pelekh" />
<meta name="copyright" content="&copy; 2009 Yurij Pelekh" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="search.js"></script>
<script type="text/javascript" src="checked.js"></script>
<title>&copy; Parodent 2010</title>
</head>

<body onload="val=<?php echo $_SESSION['val']; ?>; userList('<?php echo $_SESSION['search'] . "', " . $_SESSION['val']; ?>)">
<center>
<!-- Page --><div class="Page">
<!-- Header --><div class="Header"><?php include("header.php"); ?></div><!-- /Header -->
<!-- List --><div class="Left"><?php if ($_SESSION['page'] != "reminder") { include("list.php"); } else { include("param.php"); } ?></div><!-- /List -->

<!-- Content --><div class="Content">
<?php
if ( isset($_GET['id']) || $_SESSION['page'] == "reminder" )
{
	include("action_" . $_SESSION['page'] . ".php");
	if ( !(isset($_POST['delete']) || (isset($_POST['delete_blacklist']) && $_SESSION['page'] == "blacklist" ) || isset($_POST['archive'])) )
	{
		include("info_" . $_SESSION['page'] . ".php");
	}
}

if ( isset($_GET['add']) )
{
	include("info_" . $_SESSION['page'] . ".php");
}
?>
</div><!-- /Content -->
</div><!-- /Page -->
</center>
<?php include("system.php"); ?>
</body>
</html>