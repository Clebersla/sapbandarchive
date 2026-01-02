<?php
#################################################
#                                               #
#   Impleo Musiksamling 1.0                     #
#   (c) 2006 Christoffer Kjeldgaard Petersen    #
#   http://sappy.dk/impleo/                     #
#                                               #
#################################################

include("./functions.php");
include("./language.php");
header("content-type:text/html;charset=".getCharset()."");
if (count(installedTables()) == 0)
{
	echo $lang_noInstall;;
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="<?php echo $language; ?>">
<head>
  <title><?php echo getCollectionSettings("name") . " - " . $lang_statistics; ?></title>
  <link href="style.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<?php
echo "<h1>" . getCollectionSettings("name") . " - " . $lang_statistics . "</h1>\n";

/* Menu */
echo "<ul>\n  <li><a href=\"./\">" . $lang_frontpage . "</a></li>\n  <li><a href=\"./statistics.php\">" . $lang_statistics . "</a></li>\n</ul>\n";

/* Output lists */
if (mysql_num_rows(getRecords(0, countRecords(), "id")) > 0)
{
	echo "<h2>" . $lang_topTenArtists . "</h2>\n" . createToplist("artist", 10);
	echo "<p>" . countAttribute("artist") . " " . $lang_diffArtists . "</p>\n";
	echo "<h2>" . $lang_topTenFormats . "</h2>\n" . createToplist("format", 10);
	echo "<p>" . countAttribute("format") . " " . $lang_diffFormats . "</p>\n";
	echo "<h2>" . $lang_topTenYears . "</h2>\n" . createToplist("year", 10);
	echo "<p>" . countAttribute("year") . " " . $lang_diffYears . "</p>\n";
	echo "<h2>" . $lang_topTenLabels . "</h2>\n" . createToplist("label", 10);
	echo "<p>" . countAttribute("label") . " " . $lang_diffLabels . "</p>\n";
}
else
	echo "<p>" . $lang_noRecords . "</p>\n";
echo "<p id=\"author\">" . $authorLink . "</p>\n";
?>
</body>
</html>
