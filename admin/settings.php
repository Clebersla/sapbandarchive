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
if (isset($_POST['send']))
{
	if (ereg("[A-z0-9]+", $_POST['name']))
	{
		updateSettings($_POST['name'], $_POST['persite']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="<?php echo $language; ?>">
<head>
<title><?php echo getCollectionName() . " - " . $lang_settings; ?></title>
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
    <h2><?php echo $lang_settings; ?></h2>
    <p><?php echo $lang_mustBeFilled; ?></p>
<?php
if (isset($_POST['updatepassword']))
{
	if (ereg("[A-z0-9]+", $_POST['newpassword']))
	{
		if (controlPassword($_POST['currentpassword']))
		{		
			updatePassword($_POST['currentpassword'], $_POST['newpassword']);
			echo "    <p class=\"succes\">" . $lang_passwordChanged . "</p>\n";
		}
		else
		{
			echo "    <div class=\"error\"><p>" . $lang_errorCurrentPassword . "</p></div>\n";
		}
	}
	else
	{
		echo "    <div class=\"error\"><p>" . $lang_errorNewPassword . "</p></div>\n";
	}
}
if (isset($_POST['send']))
{
	echo "    <p class=\"succes\">" . $lang_generalSettingsUpdated . "</p>\n";
}
?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend><?php echo $lang_general; ?></legend>
      <p>
        <label for="name"><?php echo $lang_nameOfCollection; ?></label><br>
        <input type="text" id="name" name="name" tabindex="1" size="50" maxlength="100" value="<?php echo htmlspecialchars(getCollectionName()); ?>">
      </p>
<?php
$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");
$sql = mysql_query("SELECT * FROM " . $tablestart . "settings LIMIT 1");
$settingsData = mysql_fetch_array($sql);
?>
      <p>
        <?php echo $lang_recordsPrPage . "\n"; ?>
        <select name="persite" size="1" tabindex="2">
          <option value="all"<?php if ($settingsData['persite'] == "all") { echo " selected"; } ?>><?php echo $lang_showAll; ?></option>
          <option value="25"<?php if ($settingsData['persite'] == 25) { echo " selected"; } ?>>25</option>
          <option value="50"<?php if ($settingsData['persite'] == 50) { echo " selected"; } ?>>50</option>
          <option value="100"<?php if ($settingsData['persite'] == 100) { echo " selected"; } ?>>100</option>
          <option value="150"<?php if ($settingsData['persite'] == 150) { echo " selected"; } ?>>150</option>
          <option value="250"<?php if ($settingsData['persite'] == 250) { echo " selected"; } ?>>250</option>
          <option value="500"<?php if ($settingsData['persite'] == 500) { echo " selected"; } ?>>500</option>
        </select>
      </p>  
      <p><input type="submit" name="send" value="<?php echo $lang_update; ?>" tabindex="3"></p>
    </fieldset>
    </form>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <fieldset>
        <legend><?php echo $lang_changePassword; ?></legend>
        <p>
          <label for="currentpassword"><?php echo $lang_currentPassword; ?></label><br>
          <input type="password" id="currentpassword" name="currentpassword" tabindex="4" size="50" maxlength="100">
        </p>
        <p>
          <label for="newpassword"><?php echo $lang_newPassword; ?></label> <span class="star">*</span><br>
          <input type="password" id="newpassword" name="newpassword" tabindex="5" size="50" maxlength="100">
        </p>
        <p><input type="submit" name="updatepassword" value="<?php echo $lang_changePassword; ?>" tabindex="6"></p>
      </fieldset>
    </form>
  </div>
  <div id="bottom">
    <p><?php echo $authorLink; ?></p>
  </div>
</div>
</body>
</html>
