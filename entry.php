<?php
include_once("settings.php");

$counter = new Counter();
$counter->update();

if (isset($_POST["blogid"])) {
  $blogid = $_POST["blogid"];

  $name_name = $_POST["name_name"];
  $email_name = $_POST["email_name"];
  $url_name = $_POST["url_name"];
  $body_name = $_POST["body_name"];

  $name = $_POST[$name_name];
  $email = $_POST[$email_name];
  $url = $_POST[$url_name];
  $body = $_POST[$body_name];
  
  if (empty($blogid) || empty($name) || empty($body)) {
    echo "input name and comment";
    exit();
  }
  if ($url == "http://") {
    $url = "";
  }
  $t = time();
  $name = trim(strip_tags($name));
  $email = trim(strip_tags($email));
  $url = trim(strip_tags($url));
  $body = nl2br(trim(htmlspecialchars($body)));
  Comment::writeComment($blogid, $name, $email, $url, $body, $t);

  // Remembering 30 days
  setcookie('w_id',    $blogid, $t+2592000);
  setcookie('w_name',  $name,   $t+2592000);
  setcookie('w_email', $email,  $t+2592000);
  setcookie('w_url',   $url,    $t+2592000);

  $entry = Entry::getEntry($blogid);
  $temp = new UserTemplate("entry.tpl", $blogid);
  $temp->clearCache();
  header("Location: " . $entry->getHref() . "#" . $t);
  exit;
} else if (isset($_GET["blogid"]) == false) {
  echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
  exit;
}

$blogid = $_GET["blogid"];

$template = new UserTemplate('entry.tpl', $blogid);
if (!$template->is_cached('entry.tpl', $blogid)) {
  $entry = Entry::getEntry($blogid);
  if ($entry->isSetOption("SECRET")) {
    exit;
  }
  $template->assign('entry', $entry);
  $template->assign('trackbacks', $entry->getTrackbacks());
  $template->assign('comments', $entry->getComments());
  $template->assign('name_name', rand(1, 1000));
  $template->assign('email_name', rand(1001, 2000));
  $template->assign('url_name', rand(2001, 3000));
  $template->assign('body_name', rand(3001, 4000));    
}

foreach (array('w_id','w_name','w_email','w_url') as $key) {
  if (isset($HTTP_COOKIE_VARS[$key])) {
    $template->assign("$key", $HTTP_COOKIE_VARS[$key]);
  }
}

$template->display('entry.tpl', $blogid);

# vim: ts=8 sw=2 sts=2 noet
?>
