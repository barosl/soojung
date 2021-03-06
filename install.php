<?php
include_once("classes/Soojung.class.php");
include_once("libs/util.php");

if (get_magic_quotes_gpc()) {
  function stripslashes_deep($value) {
    $value = is_array($value) ?
      array_map('stripslashes_deep', $value) :
      stripslashes($value);
    return $value;
  }
  $_POST = array_map('stripslashes_deep', $_POST);
  $_GET = array_map('stripslashes_deep', $_GET);
  $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>install</title>
</head>
<body>

<?php
if (file_exists('config.php')) {
  die("already installed");
}
if (!is_writable(".")) {
  die("<font color=\"red\">WARNING: This directory is not writeable</font><br />");
}
if (!is_writable("templates")) {
  die("<font color=\"red\">WARNING: templates directory is not writeable</font><br />");
}

if (isset($_POST["name"])) {
  if (empty($_POST["name"]) || empty($_POST["desc"])
      || empty($_POST["url"]) || empty($_POST["password"])
      || empty($_POST["admin"]) || empty($_POST["email"])
      || empty($_POST["perpage"])) {
    echo "<font color=\"red\">WARNING: Input blog name, description, url, name, email, password</font><br />";
  } else {
    mkdir("contents", 0777);
    mkdir("contents/upload", 0777);
    mkdir("contents/.bookmark", 0777);

    mkdir("templates/.cache", 0777);
    mkdir("templates/.configs", 0777);
    mkdir("templates/.compile", 0777);

    mkdir("templates/.admin_cache", 0777);
    mkdir("templates/.admin_configs", 0777);
    mkdir("templates/.admin_compile", 0777);

    $f = fopen("contents/.info","w");
    fwrite($f, "1");
    fclose($f);

    $fb = isset($_POST["fancyurl"]) ? $_POST["fancyurl"] : "off";
    $nb = isset($_POST["notify"]) ? $_POST["notify"] : "off";
    Soojung::writeConfigFile($_POST["name"], $_POST["desc"], $_POST["url"], $_POST["perpage"],
			     $fb, $nb, $_POST["admin"], $_POST["email"],
			     md5($_POST["password"]));

    if(isset($_POST["fancyurl"]) && $_POST["fancyurl"] == "on") {
      Soojung::writeHtaccess();
    } else {
      Soojung::deleteHtaccess();
    }

    echo "install success. delete install.php file and change the soojung directory permission to 755<br />";
    echo "<a href=\"index.php\">home</a>";
    exit();
  }
}
?>

<b>* Note:</b> All fields marked with an asterisk (*) are required.<br /><br />

<form action="install.php" method="post">
<table>

<tr>
<td>* Blog Name:</td>
<td><input type="text" name="name" size="30" value="<?php echo isset($_POST['name']) ? $_POST['name'] : "" ?>"></td>
</tr>

<tr>
<td>* Blog Description:</td>
<td><input type="text" name="desc" size="50" value="<?php echo isset($_POST['desc']) ? $_POST['desc'] : "" ?>"></td>
</tr>

<tr>
<td>* Blog URL:</td>
<td><input type="text" name="url" size="50" value="<?php echo isset($_POST['url']) ? $_POST['url'] : 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) ?>"></td>
</tr>

<tr>
<td>* Admin name:</td>
<td><input type="text" name="admin" value="<?php echo isset($_POST['admin']) ? $_POST['admin'] : "" ?>"></td>
</tr>

<tr>
<td>* Admin email:</td>
<td><input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : "" ?>"></td>
</tr>

<tr>
<td>* Admin password:</td>
<td><input type="password" name="password" value="<?php echo isset($POST['password']) ? $_POST['password'] : "" ?>"></td>
</tr>

<tr>
<td>entries per page</td>
<td><input type="text" name="perpage" size="2" value="<?php echo isset($_POST['perpage']) ? $_POST['perpage'] : 5 ?>"></td>
</tr>

<tr>
<td>fancy url:</td>
<td><input type="checkbox" name="fancyurl"> ex) <i>http://site/soojung/category/2004/09/25/3.html</i></td>
</tr>

<tr>
<td>notify new comment, trackback by email</td>
<td><input type="checkbox" name="notify"></td>
</tr>

</table>
<input type="submit" value="Install">
</form>

</body>
</html>

<?php
# vim: ts=8 sw=2 sts=2 noet
?>
