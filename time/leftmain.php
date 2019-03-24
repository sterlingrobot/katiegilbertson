<?php
include 'config.inc.php';

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

// set cookie if 'Remember Me?' checkbox is checked, or reset cookie if 'Reset Cookie?' is checked //

if ($request == 'POST'){
    @$remember_me = $_POST['remember_me'];
    @$reset_cookie = $_POST['reset_cookie'];
    @$fullname = stripslashes($_POST['left_fullname']);
    @$displayname = stripslashes($_POST['left_displayname']);
	if(strlen($_POST['left_projects']) > 0) $projects = explode(',', $_POST['left_projects']);
    if ((isset($remember_me)) && ($remember_me != '1')) {echo "Something is fishy here.\n"; exit;}
    if ((isset($reset_cookie)) && ($reset_cookie != '1')) {echo "Something is fishy here.\n"; exit;}

    // begin post validation //

    if ($show_display_name == "yes") {

        if (isset($displayname)) {
            $displayname = addslashes($displayname);
            $query = "select displayname from ".$db_prefix."jobs where displayname = '".$displayname."'";
            $emp_name_result = mysqli_query($db, $query);

            while ($row = mysqli_fetch_assoc($emp_name_result)) {
                $tmp_displayname = "".$row['displayname']."";
            }
            if ((!isset($tmp_displayname)) && (!empty($displayname))) {echo "Jobname is not in the database.\n"; exit;}
            $displayname = stripslashes($displayname);
        }

    }

    elseif ($show_display_name == "no") {

        if (isset($fullname)) {
            $fullname = addslashes($fullname);
            $query = "select jobname from ".$db_prefix."jobs where jobname = '".$fullname."'";
            $emp_name_result = mysqli_query($query);

            while ($row = mysqli_fetch_assoc($emp_name_result)) {
                $tmp_jobname = "".$row['jobname']."";
            }
            if ((!isset($tmp_jobname)) && (!empty($fullname))) {echo "Jobname is not in the database.\n"; exit;}
            $fullname = stripslashes($fullname);
        }

    }

    // end post validation //

    if (isset($remember_me)) {

        if ($show_display_name == "yes") {
            setcookie("remember_me", stripslashes($displayname), time() + (60 * 60 * 24 * 365 * 2));
        }

        elseif ($show_display_name == "no") {
            setcookie("remember_me", stripslashes($fullname), time() + (60 * 60 * 24* 365 * 2));
        }

    }

    elseif (isset($reset_cookie)) {
        setcookie("remember_me", "", time() - 3600);
    }

    ob_end_flush();
}

echo "<div class='row'>\n";

// If there are errors with the form submission, let's show an alert above the form here
if ($request == 'POST') {

	$errors = 0;
    // signin/signout data passed over from timeclock.php //

    $inout = $_POST['left_inout'];
    $notes = preg_replace("/[^[:alnum:] \,\.\?-]/","",strtolower($_POST['left_notes']));

    // begin post validation //

    if ($use_passwd == "yes") {
        $employee_passwd = crypt($_POST['employee_passwd'], 'xy');
    }

    $query = "select punchitems from ".$db_prefix."punchlist";
    $punchlist_result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($punchlist_result)) {
        $tmp_inout = "".$row['punchitems']."";
    }

    if (!isset($tmp_inout)) {echo "<div class='alert alert-danger'>In/Out Status is not in the database.</div>\n"; exit;}

    // end post validation //

    if ($show_display_name == "yes") {

        if (!$displayname && !$inout) {
            echo "<div class='alert alert-warning'>You have not chosen a username or a status. Please try again.</div>\n";
            $errors++;
        }

        if (!$displayname) {
            echo "<div class='alert alert-warning'>You have not chosen a username. Please try again.</div>\n";
            $errors++;
        }

    }

    elseif ($show_display_name == "no") {

        if (!$fullname && !$inout) {
            echo "<div class='alert alert-warning'>You have not chosen a username or a status. Please try again.</div>\n";
            $errors++;
        }

        if (!$fullname) {
            echo "<div class='alert alert-warning'>You have not chosen a username. Please try again.</div>\n";
            $errors++;
        }

    }

    if (!$inout) {
        echo "<div class='alert alert-warning'>You have not chosen a status. Please try again.</div>\n";
        $errors++;
    }

    @$fullname = addslashes($fullname);
    @$displayname = addslashes($displayname);

	@$name_array = explode(' ', $displayname);
	@$firstname = $name_array[0];

    // configure timestamp to insert/update //

    $time = time();
    $hour = gmdate('H',$time);
    $min = gmdate('i',$time);
    $sec = gmdate('s',$time);
    $month = gmdate('m',$time);
    $day = gmdate('d',$time);
    $year = gmdate('Y',$time);
    $tz_stamp = mktime ($hour, $min, $sec, $month, $day, $year);

	if($errors === 0) {
		if ($use_passwd == "no") {

			if ($show_display_name == "yes") {

				$sel_query = "select jobname from ".$db_prefix."jobs where displayname = '".$displayname."'";
				$sel_result = mysqli_query($db, $sel_query);

				while ($row=mysqli_fetch_assoc($sel_result)) {
					$fullname = stripslashes("".$row["jobname"]."");
					$fullname = addslashes($fullname);
				}
			}

			if (strtolower($ip_logging) == "yes") {
				$query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress) values ('".$fullname."', '".$inout."',
						  '".$tz_stamp."', '".$notes."', '".$connecting_ip."')";
			} else {
				$query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes) values ('".$fullname."', '".$inout."', '".$tz_stamp."',
						  '".$notes."')";
			}

			$result = mysqli_query($db, $query);

			$update_query = "update ".$db_prefix."jobs set tstamp = '".$tz_stamp."' where jobname = '".$fullname."'";
			$other_result = mysqli_query($db, $update_query);

	//        echo "<head>\n";
	//        echo "<meta http-equiv='refresh' content=0;url=index.php>\n";
	//        echo "</head>\n";

		} else {

		  if ($show_display_name == "yes") {
			  $sel_query = "select jobname, employee_passwd from ".$db_prefix."jobs where displayname = '".$displayname."'";
			  $sel_result = mysqli_query($db, $sel_query);

			  while ($row=mysqli_fetch_assoc($sel_result)) {
				  $tmp_password = "".$row["employee_passwd"]."";
				  $fullname = "".$row["jobname"]."";
			  }

			  $fullname = stripslashes($fullname);
			  $fullname = addslashes($fullname);

		  } else {

			  $sel_query = "select jobname, employee_passwd from ".$db_prefix."jobs where jobname = '".$fullname."'";
			  $sel_result = mysqli_query($db, $sel_query);

			  while ($row=mysqli_fetch_assoc($sel_result)) {
				  $tmp_password = "".$row["employee_passwd"]."";
			  }

		  }

		  if ($employee_passwd == $tmp_password && $errors === 0) {

			  $last_time_query = "select e.tstamp, i.inout from ".$db_prefix."jobs e left join ".$db_prefix."info i on i.timestamp = e.tstamp AND i.fullname = e.jobname where jobname='" . $fullname . "' limit 1";
			  $last_time_array = mysqli_fetch_assoc(mysqli_query($db, $last_time_query));
			  $last_time = $last_time_array['tstamp'];

			  if($inout != $last_time_array['inout']) {	  // prevent duplicate ins and outs

				if (strtolower($ip_logging) == "yes") {
					$query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress) values ('".$fullname."', '".$inout."',
							  '".$tz_stamp."', '".$notes."', '".$connecting_ip."')";
				} else {
					$query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes) values ('".$fullname."', '".$inout."', '".$tz_stamp."',
							  '".$notes."')";
				}
				$result = mysqli_query($db, $query);

				$update_query = "update ".$db_prefix."jobs set tstamp = '".$tz_stamp."' where jobname = '".$fullname."'";
				$other_result = mysqli_query($db, $update_query);

                if($inout === 'lunch') echo "<script>$(function() { $('#lunchModal').modal(); setTimeout('clearModal(\"#lunchModal\")', 5000); });</script>";
                elseif($inout === 'out') echo "<script>$(function() { $('#clockoutModal').modal(); setTimeout('clearModal(\"#clockoutModal\")', 5000); });</script>";
                else echo "<script>$(function() { $('#clockinModal').modal(); setTimeout('clearModal(\"#clockinModal\")', 10000); });</script>";

			  } else { // duplicate entry of IN, OUT, Lunch
				echo "<script>$(function() { $('#dupeModal').modal(); setTimeout('clearModal(\"#dupeModal\")', 3000); });</script>";
			  }
			} else {

			  if ($show_display_name == "yes") {
				  $strip_fullname = stripslashes($displayname);
			  } else {
				  $strip_fullname = stripslashes($fullname);
			  }

			  echo "<script>$(function() { $('#wrongpassModal').modal(); setTimeout('refresh()', 1000); });</script>";
			  $retry = true;
			}
		}
	}
}
// display form to submit signin/signout information //

echo "      <form class='form' name='timeclock' action='$self' method='post' role='form'>\n";
echo "		  <div class='form-group'>";
//echo "			<label class='control-label' for='left_displayname'>Name:</label>\n";

// query to populate dropdown with employee names //

if ($show_display_name == "yes") {

    $query = "select displayname,groups,office from ".$db_prefix."jobs where disabled <> '1'  and jobname <> 'admin' order by displayname";
    $emp_name_result = mysqli_query($db, $query);
    echo "              <select id='name_entry' class='form-control input-lg ' style='background: #39b3d7; color: #FFF;' name='left_displayname' tabindex=1>\n";
    echo "              <option id='name_select' disabled selected value =''>&nbsp;Select job...</option>\n";
    while ($row = mysqli_fetch_assoc($emp_name_result)) {
        $abc = stripslashes("".$row['displayname']."");

        if ((isset($_COOKIE['remember_me']) && (stripslashes($_COOKIE['remember_me']) == $abc))
				|| (isset($displayname) && $row['displayname'] == $displayname && $retry)) {
            echo "              <option selected>$abc</option>\n";
        } else {
            echo "              <option>$abc</option>\n";
        }

    }

    echo "              </select>
                        <button class='addproject'>&plus;</button>
					  </div>\n";
    mysqli_free_result($emp_name_result);

} else {

    $query = "select jobname from ".$db_prefix."jobs where disabled <> '1'  and jobname <> 'admin' order by jobname";
    $emp_name_result = mysqli_query($db, $query);
    echo "              <select id='name_entry' class='form-control input-lg' style='background: #39b3d7; color: #FFF;' name='left_fullname' tabindex=1>\n";
    echo "              <option id='name_select' disabled selected value =''>&nbsp;Name...</option>\n";

    while ($row = mysqli_fetch_assoc($emp_name_result)) {

        $def = stripslashes("".$row['jobname']."");
        if (((isset($_COOKIE['remember_me'])) && (stripslashes($_COOKIE['remember_me']) == $def))) {
            echo "              <option selected>$def</option>\n";
        } else {
            echo "              <option>$def</option>\n";
        }

    }

    echo "              </select>
                        <button class='addproject'>&plus;</button>
					  </div>\n";
    mysqli_free_result($emp_name_result);
}

// determine whether to use encrypted passwords or not //

if ($use_passwd == "yes") {
	echo "		  <div class='form-group'>";
//    echo "			<label class='control-label' for='employee_passwd'>Password:</label>\n";
    echo "			  <input class='form-control text-center' disabled type='text' id='employee_pswd' name='employee_passwd' maxlength='25' placeholder='Key in password...' tabindex=2 />\n";
	include 'keypad.php';
	echo "		  </div>";
}
echo "		<div class='row'>&nbsp;</div>";
echo "		<div class='row'>";
//echo "        <label class='control-label' for='left_inout'>In/Out:</label><div class='clearfix'></div>\n";

// query to populate dropdown with punchlist items //

$query = "select punchitems from ".$db_prefix."punchlist";
$punchlist_result = mysqli_query($db, $query);

$index = 3;
while ($row = mysqli_fetch_assoc($punchlist_result)) {
	$click = "";
    switch ($row['punchitems']) {
	  case 'in' :
		$btn_class = 'btn-success projects_box';
		$click = "onclick='submitTime(\"in\");'";
		break;
	  case 'out' :
		$btn_class = 'btn-danger';
		$click = "onclick='submitTime(\"out\");'";
		break;
	  case 'lunch' :
		$btn_class = 'btn-primary';
		$click = "onclick='submitTime(\"lunch\");'";
		break;
	}
	if($index < 6) {
	  echo "              <div class='col-xs-4'><input type='button' id='".$row['punchitems']."_btn' class='btn btn-block $btn_class' name='left_inout' value='".$row['punchitems']."' tabindex=".$index." $click /></div>\n";
	}
	$index++;
}
echo "				  <div class='clearfix'> </div>";
echo "				  </div>\n";
mysqli_free_result( $punchlist_result );
echo "		  <div class='form-group'>";
//echo "			<label class='control-label' for='left_notes'>Notes:</label>\n";
echo "			  <textarea class='form-control' name='left_notes' maxlength='250' rows='1' placeholder='Add a note...' tabindex=".$index."></textarea>\n";
echo "			  </div>";

//if (!isset($_COOKIE['remember_me'])) {
//    echo "        <tr><td width=100%><table width=100% border=0 cellpadding=0 cellspacing=0>
//                  <tr><td nowrap height=4 align=left valign=middle class=misc_items width=10%>Remember&nbsp;Me?</td><td width=90% align=left
//                    class=misc_items style='padding-left:0px;padding-right:0px;' tabindex=5><input type='checkbox' name='remember_me' value='1'></td></tr>
//                    </table></td><tr>\n";
//}
//
//elseif (isset($_COOKIE['remember_me'])) {
//    echo "        <tr><td width=100%><table width=100% border=0 cellpadding=0 cellspacing=0>
//                  <tr><td nowrap height=4 align=left valign=middle class=misc_items width=10%>Reset&nbsp;Cookie?</td><td width=90% align=left
//                    class=misc_items style='padding-left:0px;padding-right:0px;' tabindex=5><input type='checkbox' name='reset_cookie' value='1'></td></tr>
//                    </table></td><tr>\n";
//}
echo "			<input type='hidden' name='left_projects' id='projects_input' value='' />\n";
echo "        </form>\n";  //<tr><td height=4 align=left valign=middle class=misc_items><input type='submit' name='submit_button' value='Submit' align='center' tabindex=6></td></tr>
echo "      </div>\n";

?>
