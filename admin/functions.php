<?php
#################################################
#                                               #
#   Impleo Musiksamling 1.0                     #
#   (c) 2006 Christoffer Kjeldgaard Petersen    #
#   http://sappy.dk/impleo/                     #
#                                               #
#################################################

function controlLogin()
{
	if (!$_SESSION['login'] == 1)
	{
		header("Location: ./");
		exit;
	}
}

function controlInput($input)
{
	for ($i = 0; $i < count($input); $i++)
	{
		if (!ereg("[A-z0-9]+", $input[$i]))
		{
			return false;
		}
	}
	if (!is_numeric($input[2]))
	{
		return false;
	}
	return true;
}

function installControl()
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$tables = array("collection", "login", "settings");
	for ($i = 0; $i < count($tables); $i++)
	{
		$tableControl = mysql_query("SHOW TABLES like '" . $tablestart . $tables[$i] . "'");
		if(mysql_fetch_row($tableControl) === false)
			return false;
		return true;
	}
}

function getCollectionName()
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$getSettings = mysql_query("SELECT name FROM " . $tablestart . "settings LIMIT 1");
	$settingsData = mysql_fetch_array($getSettings);
	mysql_close($database);
	return $settingsData['name'];
}

function createErrorList($input)
{
	require("../language.php");
	$list = "      <ul>\n";
	if (!ereg("[A-z0-9]+", $input[0])) { $list .= "        <li>" . $lang_artist . "</li>\n"; }
	if (!ereg("[A-z0-9]+", $input[1])) { $list .= "        <li>" . $lang_title . "</li>\n"; }
	if (!ereg("[A-z0-9]+", $input[2]) || !is_numeric($input[2])) { $list .= "        <li>" . $lang_year . "</li>\n"; }
	if (!ereg("[A-z0-9]+", $input[3])) { $list .= "        <li>" . $lang_format . "</li>\n"; }
	if (!ereg("[A-z0-9]+", $input[4])) { $list .= "        <li>" . $lang_label . "</li>\n"; }
	$list .= "      </ul>\n";
	return $list;
}

function insert($table, $input)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = "INSERT INTO `" . $tablestart . $table . "` ( `artist` , `title` , `year` , `format` , `label` , `comment` ) VALUES (";
	for ($i = 0; $i < count($input); $i++)
	{
		$sql .= "'" . str_replace("'", "&#39;", $input[$i]) . "', ";
	}
	$sql = substr($sql, 0, -2);
	$sql .= ");";
	mysql_query($sql) or die("<p>" . mysql_error() . "</p>\n");
	mysql_close($database);
}

function update($table, $id, $input)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = "UPDATE `" . $tablestart . $table . "` SET `artist` = '" . $input[0] . "', `title` = '" . $input[1] . "', `year` = '" . $input[2] . "', `format` = '" . $input[3] . "', `label` = '" . $input[4] . "', `comment` = '" . $input[5] . "' WHERE `id` = " . $id . " LIMIT 1;";
	mysql_query($sql) or die("<p>" . mysql_error() . "</p>\n");
	mysql_close($database);
}

function deleteRecord($table, $record)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	mysql_query("DELETE FROM `" . $tablestart . $table . "` WHERE `id` = " . $record . " LIMIT 1");
	mysql_close($database);
}

function controlRecord($id)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$controlVar = mysql_query("SELECT id FROM " . $tablestart . "collection WHERE id = '" . $id . "'");
	if (mysql_num_rows($controlVar) == 1)
	{
		mysql_close($database);
		return true;
	}
	else
	{
		mysql_close($database);
		return false;
	}
}

function getRecordDetails($id)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$getDetails = mysql_query("SELECT * FROM " . $tablestart . "collection WHERE id = " . $id . " LIMIT 1");
	$recordData = mysql_fetch_array($getDetails);
	mysql_close($database);
	return $recordData;
}

function updateSettings($name, $persite)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = "UPDATE `" . $tablestart . "settings` SET `name` = '" . $name . "', `persite` = '" . $persite . "' WHERE `id` = 1 LIMIT 1;";
	mysql_query($sql) or die("<p>" . mysql_error() . "</p>\n");
	mysql_close($database);
}

function controlPassword($password)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$getSettings = mysql_query("SELECT password FROM " . $tablestart . "login LIMIT 1");
	$settingsData = mysql_fetch_array($getSettings);
	mysql_close($database);
	if ($settingsData['password'] == md5($password))
		return true;
	else
		return false;
}

function updatePassword($current, $new)
{
	require("../config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = "UPDATE `" . $tablestart . "login` SET `password` = '" . md5($new) . "' WHERE `password` = '" . md5($current) . "' LIMIT 1;";
	mysql_query($sql) or die("<p>" . mysql_error() . "</p>\n");
	mysql_close($database);
}

?>
