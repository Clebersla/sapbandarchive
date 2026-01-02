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
header("content-type:text/html;charset=".$charset."");
$database = @mysql_connect($host, $user, $pass) or die("<p>" . mysql_error() . "</p>\n");
@mysql_select_db($dat, $database) or die("<p>" . mysql_error() . "</p>\n");

if(!isset($_POST['login']))
{
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=./\">";
    exit;
}
else
{
    $postbruger = $_POST['username'];
    $postpass = md5($_POST['password']); 
    $resultat = mysql_query("SELECT * FROM " . $tablestart . "login WHERE brugernavn = '$postbruger' AND password = '$postpass'") or die("<p>" . mysql_error() . "</p>\n");
    $number = mysql_num_rows($resultat);
    if($number == 1)
	{
    	$_SESSION['login'] = 1;
    	$_SESSION['brugernavn'] = $postbruger;
    	$_SESSION['password'] = $postpass;
    	header("Location: add.php");
	}
	else
	{
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="<?php echo $language; ?>">
<head>
<title><?php echo $lang_name . " - " . $lang_login; ?></title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen">
<link href="login.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<div id="logincontainer">
  <div id="logintop">
    <h1><?php echo $lang_nameLogin; ?></h1>
  </div>
  <div id="loginbody">
    <p class="error"><?php echo $lang_loginError; ?></p>
    <form id="pro" method="post" action="login.php">
      <p><label for="username"><?php echo $lang_username; ?></label><br><input type="text" name="username" id="username" maxlength="100" alt="<?php echo $lang_username; ?>" value="<?php echo $postbruger; ?>"></p>
      <p><label for="password"><?php echo $lang_password; ?></label><br><input type="password" name="password" id="password" maxlength="100" alt="<?php echo $lang_password; ?>"></p>
      <p><input type="submit" name="login" value="<?php echo $lang_login; ?>"></p>
    </form>
  </div>
  <div id="loginbottom">
    <p><?php echo $authorLink; ?></p>
  </div>
</div>
</body>
</html>
<?php
	}
}
?>
