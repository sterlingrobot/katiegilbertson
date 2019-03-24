<?php

// display user info //
if (isset($_SESSION['valid_user'])) {
    $logged_in_user = $_SESSION['valid_user'];
    echo "    <div class='alert alert-success' style='position: relative; margin-right: -15px;'><span class='glyphicon glyphicon-user'></span>\n";
    echo " Logged in as: $logged_in_user.\n";
}
else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo "    <div class='alert alert-success' style='position: relative; margin-right: -15px;'><span class='glyphicon glyphicon-user'></span>\n";
    echo " Logged in as: $logged_in_user.\n";
}
else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo "    <div class='alert alert-success' style='position: relative; margin-right: -15px;'><span class='glyphicon glyphicon-user'></span>\n";
    echo " Logged in as: $logged_in_user.\n";
 }
if ((isset($_SESSION['valid_user'])) || (isset($_SESSION['valid_reports_user'])) || (isset($_SESSION['time_admin_valid_user']))) {
    echo "    <a class='close' href='../logout.php' title='Log out' style='position: absolute; top: 0; right: 5px;' >&times;</a></div>";
}
?>
