<?php
#################################################
#                                               #
#   Impleo Musiksamling 1.0                     #
#   (c) 2006 Christoffer Kjeldgaard Petersen    #
#   http://sappy.dk/impleo/                     #
#                                               #
#################################################

include("../config.php");
include("../language.php");
header("content-type:text/html;charset=".$charset."");
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
    <form id="pro" method="post" action="login.php">
      <p><label for="username"><?php echo $lang_username; ?></label><br><input type="text" name="username" id="username" maxlength="100" alt="<?php echo $lang_username; ?>"></p>
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

