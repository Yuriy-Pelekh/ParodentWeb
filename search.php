<?php
session_start();

$_SESSION['search'] = $_GET["str"];
$_SESSION['val'] = $_GET["val"];

$str = $_GET["str"];
$val = $_GET["val"];

do {
	$str = str_replace('  ', ' ', $str, $count);
} while ($count);

// $val == 0 - Search by LastName
// $val == 1 - Search by FirstName and MiddleName

$table = $_SESSION['page'];

include("connect.php");
include("functions.php");

if ($table == "blacklist")
{
	if ($val)
	{
		if (strlen($str) > 0)
		{
			$wordCount = substr_count($str, " ");
			if ($wordCount == 2)
				list($firstName, $middleName, $lastName) = explode(" ", $str);
			elseif ($wordCount == 1)
			{
				list($firstName, $middleName) = explode(" ", $str);
				$lastName = "";
			}
			elseif ($wordCount == 0)
			{
				list($firstName) = explode(" ", $str);
				$middleName = "";
				$lastName = "";
			}
			else
			{
				$firstName = "";
				$middleName = "";
				$lastName = "";
			}
			$result = mysql_query("SELECT patient.id, patient.LastName, patient.FirstName, patient.MiddleName
									FROM patient, blacklist
									WHERE patient.id=blacklist.PatId AND patient.FirstName LIKE '$firstName%' AND
																		 patient.MiddleName LIKE '$middleName%' AND
																		 patient.LastName LIKE '$lastName%'
									ORDER BY patient.FirstName, patient.MiddleName, patient.LastName");
		}
		else
		{
			$result = mysql_query("SELECT patient.id, patient.LastName, patient.FirstName, patient.MiddleName
									FROM patient, blacklist
									WHERE patient.id=blacklist.PatId
									ORDER BY patient.FirstName, patient.MiddleName, patient.LastName");
		}
		while ($row = mysql_fetch_array($result))
		{
			echo "<a href=\"?page=$table&id=$row[id]&search=$str&val=$val\" target=\"_self\">" .
				cleanup($row['FirstName']) . " " . cleanup($row['MiddleName']) . " " . cleanup($row['LastName']) . "</a><br />\n";
		}
	}
	else
	{
		if (strlen($str) > 0)
		{
			$wordCount = substr_count($str, " ");
			if ($wordCount == 2)
				list($lastName, $firstName, $middleName) = explode(" ", $str);
			elseif ($wordCount == 1)
			{
				list($lastName, $firstName) = explode(" ", $str);
				$middleName = "";
			}
			elseif ($wordCount == 0)
			{
				list($lastName) = explode(" ", $str);
				$firstName = "";
				$middleName = "";
			}
			else
			{
				$lastName = "";
				$firstName = "";
				$middleName = "";
			}
			$result = mysql_query("SELECT patient.id, patient.LastName, patient.FirstName, patient.MiddleName
									FROM patient, blacklist
									WHERE patient.id=blacklist.PatId AND LastName LIKE '$lastName%' AND
																		 FirstName LIKE '$firstName%' AND
																		 MiddleName LIKE '$middleName%'
									ORDER BY patient.LastName, patient.FirstName, patient.MiddleName");
		}
		else
		{
			$result = mysql_query("SELECT patient.id, patient.LastName, patient.FirstName, patient.MiddleName
									FROM patient, blacklist
									WHERE patient.id=blacklist.PatId
									ORDER BY patient.LastName, patient.FirstName, patient.MiddleName");
		}
		
		while ($row = mysql_fetch_array($result))
		{
			echo "<a href=\"?page=$table&id=$row[id]&search=$str&val=$val\" target=\"_self\">" .
				cleanup($row['LastName']) . " " . cleanup($row['FirstName']) . " " . cleanup($row['MiddleName']) . "</a><br />\n";
		}
	}
}
else
{
	if ($val)
	{
		if (strlen($str) > 0)
		{
			$wordCount = substr_count($str, " ");
			if ($wordCount == 2)
				list($firstName, $middleName, $lastName) = explode(" ", $str);
			elseif ($wordCount == 1)
			{
				list($firstName, $middleName) = explode(" ", $str);
				$lastName = "";
			}
			elseif ($wordCount == 0)
			{
				list($firstName) = explode(" ", $str);
				$middleName = "";
				$lastName = "";
			}
			else
			{
				$firstName = "";
				$middleName = "";
				$lastName = "";
			}
			$result = mysql_query("SELECT * FROM $table WHERE FirstName LIKE '$firstName%' AND MiddleName LIKE '$middleName%' AND LastName LIKE '$lastName%' ORDER BY FirstName, MiddleName, LastName");
		}
		else
		{
			$result = mysql_query("SELECT * FROM $table ORDER BY FirstName, MiddleName, LastName");
		}
		while ($row = mysql_fetch_array($result))
		{
			echo "<a href=\"?page=$table&id=$row[id]&search=$str&val=$val\" target=\"_self\">" .
				cleanup($row['FirstName']) . " " . cleanup($row['MiddleName']) . " " . cleanup($row['LastName']) . "</a><br />\n";
		}
	}
	else
	{
		if (strlen($str) > 0)
		{
			$wordCount = substr_count($str, " ");
			if ($wordCount == 2)
				list($lastName, $firstName, $middleName) = explode(" ", $str);
			elseif ($wordCount == 1)
			{
				list($lastName, $firstName) = explode(" ", $str);
				$middleName = "";
			}
			elseif ($wordCount == 0)
			{
				list($lastName) = explode(" ", $str);
				$firstName = "";
				$middleName = "";
			}
			else
			{
				$lastName = "";
				$firstName = "";
				$middleName = "";
			}
			$result = mysql_query("SELECT * FROM $table WHERE LastName LIKE '$lastName%' AND FirstName LIKE '$firstName%' AND MiddleName LIKE '$middleName%' ORDER BY LastName, FirstName, MiddleName");
		}
		else
		{
			$result = mysql_query("SELECT * FROM $table ORDER BY LastName, FirstName, MiddleName");
		}
		
		while ($row = mysql_fetch_array($result))
		{
			echo "<a href=\"?page=$table&id=$row[id]&search=$str&val=$val\" target=\"_self\">" .
				cleanup($row['LastName']) . " " . cleanup($row['FirstName']) . " " . cleanup($row['MiddleName']) . "</a><br />\n";
		}
	}
}
?>