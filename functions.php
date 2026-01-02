<?php
#################################################
#                                               #
#   Impleo Musiksamling 1.0                     #
#   (c) 2006 Christoffer Kjeldgaard Petersen    #
#   http://sappy.dk/impleo/                     #
#                                               #
#################################################

function getCharset()
{
	require("./config.php");
	return $charset;
}

function installedTables()
{
	require("./config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	$result = @mysql_list_tables($dat) or die("<p>" . mysql_error() . "</p>\n");
	$num_rows = mysql_num_rows($result);
	$array = array();
	for ($i = 0; $i < $num_rows; $i++)
	{
   		$array[] = mysql_tablename($result, $i);
	}
	mysql_close($database);
	return $array;
}

function getCollectionSettings($info)
{
	require("./config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$getSettings = mysql_query("SELECT " . $info . " FROM " . $tablestart . "settings LIMIT 1") or die("<p>" . mysql_error() . "</p>\n");
	$settingsData = mysql_fetch_array($getSettings);
	mysql_close($database);
	return $settingsData[$info];
}

function countRecords()
{
	require("./config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$count = mysql_num_rows(mysql_query("SELECT id FROM " . $tablestart . "collection"));
	mysql_close($database);
	return $count;
}

function countAttribute($case)
{
	require("./config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$count = mysql_num_rows(mysql_query("SELECT COUNT(id) as cnt, " . $case . " FROM " . $tablestart . "collection GROUP BY " . $case . ""));
	mysql_close($database);
	return $count;
}

function getRecords($start, $limit, $order)
{
	require("./config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = mysql_query("SELECT * FROM " . $tablestart . "collection ORDER BY " . $order . " LIMIT " . $start . ", " . $limit) or die("<p>" . mysql_error() . "</p>\n");
	mysql_close($database);
	return $sql;
}

function createPagingList($numRecords, $limit, $display, $sort)
{
	require("./language.php");
	if (ereg("[A-z0-9]+", $sort))
		$sortVar = "sort=" . $sort;
	else
		$sortVar = "sort=artist";
	$paging = ceil($numRecords/$limit);
	$pagingOutput = "<p id=\"navigation\">\n";
	if ($display > 1)
	{
		$previous = $display - 1;
		if ($previous == 1)
			$pagingOutput .= "  <a href=\"./?" . $sortVar . "\">« " . $lang_prev . "</a>\n";
		else
			$pagingOutput .= "  <a href=\"./?" . $sortVar . "&amp;show=" . $previous . "\">« " . $lang_prev . "</a>\n";
	}
	else
		$pagingOutput .= "  « " . $lang_prev . "\n";
	if ($numRecords != $limit)
	{
		for ($i = 1; $i <= $paging; $i++)
		{
			if ($display == $i)
				$pagingOutput .= "  <b>$i</b>\n";
			else
			{
				if ($i == 1)
					$pagingOutput .= "  <a href=\"./?" . $sortVar . "\">" . $i ."</a>\n";
				else
					$pagingOutput .= "  <a href=\"./?" . $sortVar . "&amp;show=" . $i . "\">" . $i . "</a>\n";
			}
		}
	}
	if ($display < $paging)
	{
		$next = $display + 1;
		$pagingOutput .= "  <a href=\"./?" . $sortVar . "&amp;show=" . $next . "\">" . $lang_next . " »</a>\n";
	}
	else
	{
		$pagingOutput .= "  " . $lang_next . " »\n";
    }
	$pagingOutput .= "</p>\n";
	return $pagingOutput;
}

function createToplist($case, $limit)
{
	require("./config.php");
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = mysql_query("SELECT COUNT(id) as cnt, " . $case . " FROM " . $tablestart . "collection GROUP BY " . $case . " ORDER BY cnt DESC LIMIT " . $limit . "") or die("<p>" . mysql_error() . "</p>\n");
	$list = "<ol>\n";
	while ($data = mysql_fetch_array($sql))
	{
		$list .= "  <li>" . $data[$case] . " (" . $data['cnt'] . ")</li>\n";
	}
	$list .= "</ol>\n";
	mysql_close($database);
	return $list;
}

?>
