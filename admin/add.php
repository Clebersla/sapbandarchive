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
$constError = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="<?php echo $language; ?>">
<head>
<title><?php echo getCollectionName() . " - " . $lang_addRecord; ?></title>
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
    <h2><?php echo $lang_addRecord; ?></h2>
<?php
if (isset($_POST['send']))
{
	$inputControl = array($_POST['kunstner'], $_POST['title'], $_POST['year'], $_POST['format'], $_POST['label']);
	$inp = array($_POST['kunstner'], $_POST['title'], $_POST['year'], $_POST['format'], $_POST['label'], $_POST['kommentar']);
	if (controlInput($inputControl))
	{
   		insert("collection", $inp);
		echo "    <p class=\"succes\">" . $lang_recordAdded . "</p>\n";
		$constError = 0;
	}
	else
	{
		echo "    <div class=\"error\">\n      <p>" . $lang_addEditError . "</p>\n";
		echo createErrorList($inputControl);
		echo "    </div>\n";
		$constError = 1;
	}
}
?>
    <p><?php echo $lang_mustBeFilled; ?></p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return tjek(this)">
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
        <input type="text" id="kunstner" name="kunstner" tabindex="1" size="50" maxlength="100" <?php if ($constError == 1) { echo "value=\"" . stripslashes($_POST['kunstner']) . "\""; } ?>>
      </p>
      <p>
        <label for="title"><?php echo $lang_title; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="title" name="title" tabindex="2" size="50" maxlength="100" <?php if ($constError == 1) { echo "value=\"" . stripslashes($_POST['title']) . "\""; } ?>>
      </p>
      <p>
        <label for="year"><?php echo $lang_releaseYear; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="year" name="year" tabindex="3" size="50" maxlength="100" <?php if ($constError == 1) { echo "value=\"" . stripslashes($_POST['year']) . "\""; } ?>>
      </p>
      <p>
        <label for="format"><?php echo $lang_format; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="format" name="format" tabindex="4" size="50" maxlength="100" <?php if ($constError == 1) { echo "value=\"" . stripslashes($_POST['format']) . "\""; } ?>>
      </p>
      <p>
        <label for="label"><?php echo $lang_label; ?>:</label> <span class="star">*</span><br>
        <input type="text" id="label" name="label" tabindex="5" size="50" maxlength="100" <?php if ($constError == 1) { echo "value=\"" . stripslashes($_POST['label']) . "\""; } ?>>
      </p>
      <p>
        <label for="kommentar"><?php echo $lang_comment; ?>:</label><br>
        <input type="text" id="kommentar" name="kommentar" tabindex="6" size="50" maxlength="100" <?php if ($constError == 1) { echo "value=\"" . stripslashes($_POST['kommentar']) . "\""; } ?>>
      </p>
    </fieldset>
    <p><input type="submit" name="send" value="<?php echo $lang_addRecord; ?>" tabindex="7"></p>
  </div>
  <div id="bottom">
    <p><?php echo $authorLink; ?></p>
  </div>
</div>
</body>
</html>
