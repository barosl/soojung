<?php
include_once("soojung.php");

if (isset($_POST["blogid"])) {
  $blogid = $_POST["blogid"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $url = $_POST["url"];
  $body = $_POST["body"];
  
  if (empty($blogid) || empty($name) || empty($email) || empty($url) || empty($body)) {
    echo "input name, email, url, comment";
    exit();
  }
  if ($url == "http://") {
    $url = "";
  }
  comment_write($blogid, $name, $email, $url, $body, time());
  $entry = get_entry($blogid);
  echo "<meta http-equiv='refresh' content='0;URL=" . $entry['link'] . "'>";
  exit;
} else if (isset($_GET["blogid"]) == false) {
  echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
  exit;
} else {
  $blogid = $_GET["blogid"];
  $entry = get_entry($blogid);
}
?>

<?php
$smarty = new Smarty;
$smarty->template_dir = "templates/" . $blog_skin . "/";
$smarty->compile_dir = "templates/.compile/";
$smarty->config_dir = "templates/.configs/";
$smarty->cache_dir = "templates/.cache/";

$smarty->assign('title', $blog_name);
$smarty->assign('baseurl', $blog_baseurl);

$smarty->assign('entry', $entry);
$smarty->assign('trackbacks', get_trackbacks($entry['id']));
$smarty->assign('comments', get_comments($entry['id']));

$smarty->assign('categories', get_category_list());
$smarty->assign('archvies', get_archive_list());
$smarty->assign('recent_entries', get_recent_entries());
$smarty->assign('recent_comments', get_recent_comments());
$smarty->assign('recent_trackbacks', get_recent_trackbacks());

get_count();

$smarty->assign('today_count', $today_count);
$smarty->assign('total_count', $total_count);

$smarty->display('entry.tpl');
?>