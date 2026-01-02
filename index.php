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

// Verifica se as tabelas existem no banco de dados
if (count(installedTables()) == 0)
{
	echo $lang_noInstall;
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="<?php echo $language; ?>">
<head>
  <title><?php
echo getCollectionSettings("name");
if (isset($_GET['show']) && is_numeric($_GET['show']))
	echo " - " . $lang_page . " " . $_GET['show'];
?></title>
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
echo "<h1>" . getCollectionSettings("name") . "</h1>\n";

/* Menu - Agora com a opção de Login para Upload */
echo "<ul>\n";
echo "  <li><a href=\"./\">" . $lang_frontpage . "</a></li>\n";
echo "  <li><a href=\"./statistics.php\">" . $lang_statistics . "</a></li>\n";
echo "  <li><a href=\"./admin.php\">" . $lang_login . "</a></li>\n";
echo "</ul>\n";

/* Limit-control */
if (getCollectionSettings("persite") == "all")
	$limit = countRecords();
else
	$limit = getCollectionSettings("persite");

/* Page-control */
if (isset($_GET['show']) && is_numeric($_GET['show']))
{
	$display = $_GET['show'];
	$start = $display*$limit-$limit;
}
else
{
	$display = 1;
	$start = 0;
}

/* Sort-control */
if (isset($_GET['sort']))
{
	$sort = $_GET['sort'];
	$order = $sort . ", id";
}
else
{
	$sort = "";
	$order = "artist, year, title, format, label";
}

/* Output list */
$records = getRecords($start, $limit, $order);
$num = mysqli_num_rows($records); // Atualizado para mysqli
if ($num > 0)
{
	echo "<table>
  <caption>" . countRecords() . " " . $lang_records . " (" . countAttribute("artist") . " " . $lang_artists . ").</caption>
  <thead>
    <tr>
      <th id=\"artist\"><a href=\"?sort=artist\" title=\"" . $lang_sort . "\">" . $lang_artist . "</a></th>
      <th id=\"title\"><a href=\"./?sort=title\" title=\"" . $lang_sort . "\">" . $lang_title . "</a></th>
      <th id=\"year\"><a href=\"?sort=year\" title=\"" . $lang_sort . "\">" . $lang_year . "</a></th>
      <th id=\"format\"><a href=\"?sort=format\" title=\"" . $lang_sort . "\">" . $lang_format . "</a></th>
      <th id=\"label\"><a href=\"?sort=label\" title=\"" . $lang_sort . "\">" . $lang_label . "</a></th>
      <th id=\"comment\"><a href=\"?sort=comment\" title=\"" . $lang_sort . "\">" . $lang_comment . "</a></th>
    </tr>
  </thead>
  <tbody>\n";
	while ($data = mysqli_fetch_array($records)) // Atualizado para mysqli
	{
		echo "    <tr>
      <td headers=\"artist\">" . $data['artist'] . "</td>
      <td headers=\"title\">" . $data['title'] . "</td>
      <td headers=\"year\">" . $data['year'] . "</td>
      <td headers=\"format\">" . $data['format'] . "</td>
      <td headers=\"label\">" . $data['label'] . "</td>
      <td headers=\"comment\">" . $data['comment'] . "</td>
    </tr>\n";
	}
	echo "  </tbody>\n</table>\n";

	if (countRecords() > $limit)
		echo createPagingList(countRecords(), $limit, $display, $sort);
}
else
	echo "<p>" . $lang_noRecords . "</p>\n";
echo "<p id=\"author\">" . $authorLink . "</p>\n";
?>
</body>
</html>
