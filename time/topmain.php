<?php

echo "<div id='header' class='row'>";
// display the logo in top left of each page. This will be $logo you setup in config.inc.php. //
// It will also link you back to your index page. //

//if ($logo != "none") echo "<a class='col-sm-2' href='index.php'><img border=0 src='$logo'></a>";

// display today's date under the logo of each page. This will link to $date_link you setup in config.inc.php. //
// display a 'reset cookie' message if $use_client_tz = "yes" //


if ($date_link == "none") {

    if ($use_client_tz == "yes") {
        echo "    <span class=notprint style='font-size:9px;'>
              <p>If the times below appear to be an hour off, click <a href='resetcookie.php' style='font-size:9px;'>here</a> to reset.<br />
                If that doesn't work, restart your web browser and reset again.</p></span>\n";
    }
	$todaydate=date('F j, Y');
	echo "<h3 id='date' class='col-sm-3 col-xs-12 text-primary text-center'>$todaydate</h3>\n";
	echo "<h3 id='clock' class='col-sm-3 col-xs-12 text-primary text-center' > </h3>";

} else {

    if ($use_client_tz == "yes") {
        echo "    <span class=notprint style='font-size:9px;'>
              <p>If the times below appear to be an hour off, click <a href='resetcookie.php' style='font-size:9px;'>here</a> to reset.<br />
                If that doesn't work, restart your web browser and reset again.</p></span>\n";
    }

    echo "    <a class='col-md-6' href='$date_link'>";
	$todaydate=date('M j, Y');
	echo "$todaydate<br/>\n";
	echo "<div id='clock'> </div></a>";

}




// if db is out of date, report it here //

if (($dbexists <> "1") || (@$my_dbversion <> $dbversion)) {
    echo "    <div class='alert alert-warning'><p>***Your database is out of date.***<br />
                                                                                    &nbsp;&nbsp;&nbsp;Upgrade it via the admin section.</p></div>\n";
}


// display links to calendars //

if ($links != "none") {
    for ($x=0; $x<count($display_links); $x++) {
	    $off = ($x === 0)? 'col-sm-offset-1' : '';
        echo "        <div class='col-sm-3 col-xs-6 $off'><a class='btn btn-block btn-info calendar' data-fancybox-type='iframe' href='$links[$x]' target='_blank' ><span class='glyphicon glyphicon-calendar'></span> $display_links[$x]</a></div>";
    }

}
// HOME  |  REPORTS  |   ADMIN
echo "	  <div class='col-sm-1 col-xs-4 col-sm-offset-2'><a class='btn btn-default btn-block' href='index.php'><span class='glyphicon glyphicon-home'></span></a></div>\n";
if ($use_reports_password == "yes") {
    echo "  <div class='col-sm-1 col-xs-4'><a class='btn btn-default btn-block' href='login_reports.php'><span class='glyphicon glyphicon-list-alt'></span></a></div>\n";
}
elseif ($use_reports_password == "no") {
    echo "    <div class='col-sm-1 col-xs-4'><a class='btn btn-default btn-block report' href='reports/index.php'><span class='glyphicon glyphicon-list-alt'></span></a></div>\n";
}
if(!$is_tablet) { // Only show the Admin button for desktops, not the Nook Timeclock
  echo "	  <div class='col-sm-1 col-xs-4'><a class='btn btn-default btn-block' href='login.php'><span class='glyphicon glyphicon-briefcase'></span></a></div>\n";
}
echo "	</div>";

?>