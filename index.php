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

// --- INICIO DO INSTALADOR AUTOMATICO ---
$db = db_connect(); // Conecta usando a função com SSL que criamos

// Tabelas necessárias para o script de 2006
$sql1 = "CREATE TABLE IF NOT EXISTS `impleor7_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `persite` varchar(255) NOT NULL DEFAULT '10',
  `admin_user` varchar(255) NOT NULL,
  `admin_pass` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;";

$sql2 = "CREATE TABLE IF NOT EXISTS `impleor7_collection` (
  `id` int(11) NOT NULL auto_increment,
  `artist` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` varchar(4) NOT NULL,
  `format` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;";

mysqli_query($db, $sql1);
mysqli_query($db, $sql2);

// Verifica se já existe configuração, se não, insere o padrão
$check = mysqli_query($db, "SELECT id FROM impleor7_settings LIMIT 1");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($db, "INSERT INTO impleor7_settings (name, persite, admin_user, admin_pass) VALUES ('Arquivo da Banda', '10', 'admin', 'admin')");
}
// --- FIM DO INSTALADOR AUTOMATICO ---

header("content-type:text/html;charset=".getCharset()."");

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
  <link href="style.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<?php
echo "<h1>" . getCollectionSettings("name") . "</h1>\n";

/* Menu */
echo "<ul>\n  <li><a href=\"./\">" . $lang_frontpage . "</a></li>\n  <li><a href=\"./statistics.php\">" . $lang_statistics . "</a></li>\n</ul>\n";

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
