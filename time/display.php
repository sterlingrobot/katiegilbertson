<?php

// display user info //
echo "<div class='container'>";

if (isset($_SESSION['valid_user'])) {
    $logged_in_user = $_SESSION['valid_user'];
    echo "    <div class='alert alert-success'><span class='glyphicon glyphicon-user'></span>\n";
    echo "    Logged in as: $logged_in_user\n";
}
else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo "    <div class='alert alert-success'><span class='glyphicon glyphicon-user'></span>\n";
    echo "    Logged in as: $logged_in_user\n";
}
else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo "    <div class='alert alert-success'><span class='glyphicon glyphicon-user'></span>\n";
    echo "    Logged in as: $logged_in_user\n";
}
if ((isset($_SESSION['valid_user'])) || (isset($_SESSION['valid_reports_user'])) || (isset($_SESSION['time_admin_valid_user']))) {
    echo "    <a class='close' href='logout.php' title='Log out' >&times;</a></div>";
}
echo "</div>";

echo "			<div class='row'>";
echo "			  <div class='panel panel-default'>";
echo "				<div class='panel-heading'>Current status, as of " . date('M d, Y g:i a');
if (!isset($_GET['printer_friendly']) && !$is_tablet) {
	echo "              <a class='pull-right hidden-xs' href='timeclock.php?printer_friendly=true'><span class='glyphicon glyphicon-print'></span> </a>\n";
}
echo "			  </div>";
echo "            <table class='table table-condensed table-responsive'>\n";
echo "            	<tbody>\n";

$row_count = 0;
$page_count = 0;

while ($row=mysqli_fetch_assoc($result)) {

    $display_stamp = "".$row["timestamp"]."";
    $time = date($timefmt, $display_stamp);
    $date = date($datefmt, $display_stamp);

        if ($row_count == 0) {

            if ($page_count == 0) {

                // display sortable column headings for main page //
                echo "              <tr class=notprint>\n";
                echo "                <th><a href='$current_page?sortcolumn=jobname&sortdirection=$sortnewdirection'>Name</a></th>\n";
                echo "                <th><a href='$current_page?sortcolumn=inout&sortdirection=$sortnewdirection'>In/Out</a></th>\n";
                echo "                <th><a href='$current_page?sortcolumn=tstamp&sortdirection=$sortnewdirection'>Time</a></th>\n";
                echo "                <th class='hidden-xs'><a href='$current_page?sortcolumn=tstamp&sortdirection=$sortnewdirection'>Date</a></th>\n";

                if ($display_office_name == "yes") {
                    echo "                <th class='hidden-xs'><a href='$current_page?sortcolumn=office&sortdirection=$sortnewdirection'>Dept</a></th>\n";
                }

                if ($display_group_name == "yes") {
                    echo "                <th class='hidden-xs'><a href='$current_page?sortcolumn=groups&sortdirection=$sortnewdirection'>Group</a></th>\n";
                }

                echo "                <th class='hidden-xs'><a href='$current_page?sortcolumn=notes&sortdirection=$sortnewdirection'><u>Notes</u></a></th>\n";
                echo "              </tr>\n";

            } else {

            // display report name and page number of printed report above the column headings of each printed page //

            $temp_page_count = $page_count + 1;
        }

        echo "              <tr class='hide'>\n";
        echo "                <th>Name</th>\n";
        echo "                <th>In/Out</th>\n";
        echo "                <th>Time</th>\n";
        echo "                <th class='hidden-xs'>Date</th>\n";

        if ($display_office_name == "yes") {
            echo "                <th class='hidden-xs'>Dept</th>\n";
        }

        if ($display_group_name == "yes") {
            echo "                <th class='hidden-xs'>Group</th>\n";
        }
        echo "                <th class='hidden-xs'>Notes</th>\n";
        echo "              </tr>\n";
    }

    // display the query results //

    $display_stamp = $display_stamp + @$tzo;
    $time = date($timefmt, $display_stamp);
    $date = date($datefmt, $display_stamp);

	switch($row["inout"]) {
		case "in" :
			$rowclass = "success";
		    echo "<script>var " . str_replace(' ', '_', $row["displayname"]) . " = true;</script>";
			break;
		case "out" :
			$rowclass = "danger";
			break;
		case "lunch" :
		default :
			$rowclass = "";
			break;
	}

	echo " 	<tr class='$rowclass'>";
    if ($show_display_name == "yes") {
        echo stripslashes("              <td nowrap>".$row["displayname"]."</td>\n");
    } elseif ($show_display_name == "no") {
        echo stripslashes("              <td nowrap>".$row["jobname"]."</td>\n");
    }

    echo "                <td>".$row["inout"]."</td>\n";
    echo "                <td>".$time."</td>\n";
    echo "                <td class='hidden-xs'>".$date."</td>\n";

    if ($display_office_name == "yes") {
        echo "                <td class='hidden-xs'>".$row["office"]."</td>\n";
    }

    if ($display_group_name == "yes") {
        echo "                <td class='hidden-xs'>".$row["groups"]."</td>\n";
    }

    echo stripslashes("                <td class='hidden-xs'>".$row["notes"]."</td>\n");
    echo "              </tr>\n";

    $row_count++;

    // output 40 rows per printed page //

    if ($row_count == 40) {
        echo "              <tr style=\"page-break-before:always;\"></tr>\n";
        $row_count = 0;
        $page_count++;
    }

}

echo "            	</table>\n";
echo "            </div>\n";

if($display_weather == "yes") {
  	echo "	<div id='weather' class='panel panel-info'>\n";
	echo "		  <div style='padding: 20px; text-align: center; color: #CCC;'><img src='http://www.cherrytreedesign.com/images/images/sm_graphics/loader.gif' width='20' height='20' />&nbsp;&nbsp;Loading...</div>";
	echo "	</div>";
}
mysqli_free_result($result);
?>
