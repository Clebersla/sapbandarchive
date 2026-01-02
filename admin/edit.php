<?php
#################################################
#                                               #
#   Impleo Musiksamling 1.0                     #
#   (c) 2006 Christoffer Kjeldgaard Petersen    #
#   http://sappy.dk/impleo/                     #
#                                               #
#################################################

session_start();
include("../config.php");
include("../language.php");
include("./functions.php");
header("content-type:text/html;charset=".$charset."");
controlLogin();
if (isset($_GET['logud']))
{
   unset($_SESSION['login']);
   header("Location: ./");
}
if (!installControl())
{
	header("Location: ../");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="<?php echo $language; ?>">
<head>
<title><?php echo getCollectionName() . " - " . $lang_editRecord; ?></title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<div id="container">
  <div id="header">
    <h1><?php echo getCollectionName(); ?></h1>
  </div>
  <div id="menu">
<?php
include("./menu.php");
?>
  </div>
  <div id="content">
    <h2><?php echo $lang_editRecord; ?></h2>
<?php
if (isset($_GET['delete']))
{
	if (controlRecord($_GET['delete']))
	{
		deleteRecord("collection", $_GET['delete']);
		echo "    <p class=\"succes\">" . $lang_recordDeleted . "</p>\n";
	}
}
if (isset($_GET['edit']))
{
	if (isset($_POST['send']))
	{
		$inputControl = array($_POST['kunstner'], $_POST['title'], $_POST['year'], $_POST['format'], $_POST['label']);
		$inp = array(str_replace("'", "&#39;", $_POST['kunstner']), str_replace("'", "&#39;", $_POST['title']), $_POST['year'], str_replace("'", "&#39;", $_POST['format']), str_replace("'", "&#39;", $_POST['label']), str_replace("'", "&#39;", $_POST['kommentar']));
		if (controlInput($inputControl))
		{
   			update("collection", $_GET['edit'], $inp);
			echo "    <p class=\"succes\">" . $lang_recordEdited . "</p>\n";
			echo "    <p><a href=\"" . $_SERVER['PHP_SELF'] . "\">" . $lang_editNewRecord . "</a></p>\n";
		}
		else
		{
			echo "    <div class=\"error\">\n      <p>" . $lang_addEditError . "</p>\n";
			echo createErrorList($inputControl);
			echo "    </div>\n";
		}
	}
	else
	{
		if (controlRecord($_GET['edit']))
		{
			$recordData = getRecordDetails($_GET['edit']);
			?>
    <p><?php echo $lang_mustBeFilled; ?></p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?edit=" . $recordData['id']; ?>" onsubmit="return tjek(this)">
    <script type="text/javascript">
      function tjek(form){
        if(form.elements['kunstner'].value == ""){
          alert("<?php echo $lang_jsArtist; ?>");
          form.elements['kunstner'].focus();
          return false;
        }
        if(form.elements['title'].value == ""){
          alert("<?php echo $lang_jsTitle; ?>");
          form.elements['title'].focus();
          return false;
        }
        if(form.elements['year'].value == ""){
          alert("<?php echo $lang_jsYear; ?>");
          form.elements['year'].focus();
          return false;
        }
        if(form.elements['format'].value == ""){
          alert("<?php echo $lang_jsFormat; ?>");
          form.elements['format'].focus();
          return false;
        }
        if(form.elements['label'].value == ""){
          alert("<?php echo $lang_jsLabel; ?>");
          form.elements['label'].focus();
          return false;
        }
      }
    </script>
    <fieldset>
      <legend><?php echo $lang_albuminfo; ?></legend>
      <p>
        <label for="kunstner"><?php echo $lang_artist; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="kunstner" name="kunstner" tabindex="1" size="50" maxlength="100" <?php echo "value=\"" . htmlspecialchars($recordData['artist']) . "\""; ?>>
      </p>
      <p>
        <label for="title"><?php echo $lang_title; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="title" name="title" tabindex="2" size="50" maxlength="100" <?php echo "value=\"" . htmlspecialchars($recordData['title']) . "\""; ?>>
      </p>
      <p>
        <label for="year"><?php echo $lang_releaseYear; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="year" name="year" tabindex="3" size="50" maxlength="100" <?php echo "value=\"" . htmlspecialchars($recordData['year']) . "\""; ?>>
      </p>
      <p>
        <label for="format"><?php echo $lang_format; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="format" name="format" tabindex="4" size="50" maxlength="100" <?php echo "value=\"" . htmlspecialchars($recordData['format']) . "\""; ?>>
      </p>
      <p>
        <label for="label"><?php echo $lang_label; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="label" name="label" tabindex="5" size="50" maxlength="100" <?php echo "value=\"" . htmlspecialchars($recordData['label']) . "\""; ?>>
      </p>
      <p>
        <label for="kommentar"><?php echo $lang_comment; ?>:</label><br>
        <input type="text" id="kommentar" name="kommentar" tabindex="6" size="50" maxlength="100" <?php echo "value=\"" . htmlspecialchars($recordData['comment']) . "\""; ?>>
      </p>
    </fieldset>
    <p><input type="submit" name="send" value="<?php echo $lang_editRecord; ?>" tabindex="7"></p>	
<?php
		}
		else
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $_SERVER['PHP_SELF'] . "\">";
			exit;
		}
	}
}
else
{
	$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
	@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
	$sql = mysql_query("SELECT * FROM " . $tablestart . "collection ORDER BY artist, year, title, format, label");
	$num = mysql_num_rows($sql);
	if ($num > 0)
	{
		echo "    <table cellpadding=\"2\" cellspacing=\"0\">
      <caption>" . $num;
		if ($num == 1)
			echo $lang_recordInTotal;
		else
			echo $lang_recordsInTotal;
		echo "</caption>
      <tr>
        <th id=\"kunstner\">" . $lang_artist . "</th>
        <th id=\"titel\">" . $lang_title . "</th>
        <th id=\"aar\">" . $lang_year . "</th>
        <th id=\"format\">" . $lang_format . "</th>
        <th id=\"selskab\">" . $lang_label . "</th>
        <th id=\"rediger\">" . $lang_edit . "</th>
        <th id=\"slet\">" . $lang_delete . "</th>
      </tr>\n";
		while ($data = mysql_fetch_array($sql))
		{
			echo "      <tr>
        <td headers=\"kunstner\">" . $data['artist'] . "</td>
        <td headers=\"titel\">" . $data['title'] . "</td>
        <td headers=\"aar\">" . $data['year'] . "</td>
        <td headers=\"format\">" . $data['format'] . "</td>
        <td headers=\"selskab\">" . $data['label'] . "</td>
        <td headers=\"rediger\"><a href=\"" . $_SERVER['PHP_SELF'] . "?edit=" . $data['id'] . "\">" . $lang_edit . "</a></td>
        <td headers=\"slet\"><a href=\"" . $_SERVER['PHP_SELF'] . "?delete=" . $data['id'] . "\">" . $lang_delete . "</a></td>
      </tr>\n";
		}
		echo "    </table>\n";
	}
	else
	{
		echo "<p>" . $lang_noRecords . "</p>\n";
	}
}
?>
  </div>
  <div id="bottom">
    <p><?php echo $authorLink; ?></p>
  </div>
</div>
</body>
</html>
