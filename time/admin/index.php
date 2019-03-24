<?php

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Administration</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$row_count = '0';
$row_color = ($row_count % 2) ? $color2 : $color1;

if(!isset($_SESSION['valid_user'])) {

  echo "<table class='table'>\n";
  echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Administration</td></tr>\n";
  echo "  <tr class=right_main_text>\n";
  echo "    <td align=center valign=top scope=row>\n";
  echo "      <table class='table'>\n";
  echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
  echo "        <tr class=right_main_text><td align=center>Click <a class='btn btn-default' href='../login.php'><u>here</u></a> to login.</td></tr>\n";
  echo "      </table><br /></td></tr></table>\n";
  exit;
}

include 'admin_menu.php';
include '../templates/admin_index_tpl.php';
include '../footer.php';
?>
