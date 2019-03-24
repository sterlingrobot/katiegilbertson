<?php
ini_set('display_errors', 1);

include_once 'functions.php';
//ob_start();
echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>";

// grab the connecting ip address. //

$connecting_ip = get_ipaddress();

if (empty($connecting_ip)) {
    return FALSE;
}

// determine if connecting ip address is allowed to connect to PHP Timeclock //

if ($restrict_ips == "yes") {
    for ($x=0; $x<count($allowed_networks); $x++) {
        $is_allowed = ip_range($allowed_networks[$x], $connecting_ip);
        if (!empty($is_allowed)) {
            $allowed = TRUE;
        }
    }
    if (!isset($allowed)) {
        echo "You are not authorized to view this page."; exit;
    }
}

// connect to db anc check for correct db version //

$db = mysqli_connect('p:' .$db_hostname, $db_username, $db_password, $db_name);
if (!$db) {echo "Error: Could not connect to the database. Please try again later."; exit;}

$table = "dbversion";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");

// if ($result) {
    $dbexists = "1";
// } else {
//     $dbexists = "0";
// }

$db_version_result = mysqli_query($db, "select * from ".$db_prefix."dbversion");
while (@$row = mysqli_fetch_assoc($db_version_result)) {
    @$my_dbversion = "".$row["dbversion"]."";
}

// include css and timezone offset//

if (($use_client_tz == "yes") && ($use_server_tz == "yes")) {
    $use_client_tz = '$use_client_tz';
    $use_server_tz = '$use_server_tz';
    echo "Please reconfigure your config.inc.php file, you cannot have both $use_client_tz AND $use_server_tz set to 'yes'"; exit;
}

echo "<head>\n";
if ($use_client_tz == "yes") {
    if (!isset($_COOKIE['tzoffset'])) {
        include 'tzoffset.php';
        echo "<meta http-equiv='refresh' content='0;URL=timeclock.php'>\n";
    }
}
?>

<link rel='stylesheet' type='text/css' media='screen' href='css/bootstrap-readable.min.css' />
<style type='text/css'>
  form[name="timeclock"] .btn { height: 60px; }
  form[name="timeclock"] select, form[name="timeclock"] input, form[name="timeclock"] textarea { font-size: 2em; height: 2.2em; }
  form[name="timeclock"] textarea { height: auto; }
  .row, .form-group, .form-control { margin-bottom: 6px; }
  .row { margin-left: -10px; margin-right: -10px;}
  .col-sm-6.stock { padding-left: 5px; padding-right: 5px; }
  .calendar { margin-bottom: 3px; }
  #name_entry { width:calc(100% - 65px); display:inline-block; }
  .addproject { background: #39b3d7; color: #FFF; border:0; border-radius: 5px; font-size: 3em; width: 50px; height: 50px; line-height:0; margin-left:10px;transform:translateY(10px);}
  .addproject:focus { outline:none; }
  #project_done {font: 2.5em Lora; font-weight: bold; padding: 5px;}
  #header, #wrapper { margin-left: 0; margin-right: 0; }
  #header h3 {  margin: 0; height: 40px;}
  #weather .table>tbody>tr>td, #weather .panel-heading { padding: 0 12px; }
  #projects .btn-block { margin-top: 6px; margin-bottom: 6px; padding-left: 10px; text-align: left; overflow: hidden; }
  #projects .project_labels { width: 70px; height: 40px; }
  #projects .project_label { padding: 0 4px; border-radius: 3px; font-size: 0.7em; font-weight: bold; display: block; width: 60px; margin: 1px 0; }
  #projects .project_name { display: block; margin-left: 75px; }
  form[name="form"] tbody tr:nth-child(7),
  form[name="form"] tbody tr:nth-child(8),
  form[name="form"] tbody tr:nth-child(9),
  form[name="form"] tbody tr:nth-child(10) { display: none; }
</style>

<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
<script>
  	$(function() {
	  $('#contentLoading').css('display', 'none')
          $('#name_entry').focus().on('change', function () {
            dispname = $('#name_entry').val();
            rpoptions  = {
                    displayname : dispname,
                    from_date : sun,
                    to_date : sat,
                    tmp_paginate : 0,
                    tmp_round_time : 3,
                    tmp_show_details : 1,
                    tmp_display_ip : 0,
                    tmp_deduct_lunch : 1
            }
          });
          $('#in_btn').on('click', function() { submitTime("in"); })
	  $('.calendar').on('click', function (e) {
		  e.preventDefault();
//		  alert($(this).attr('href'));
		  $("#calTitle").html($(this).html()+" Calendar");
		  $("#calBody").html('<iframe src="'+$(this).attr('href')+'" style="width: 100%; height:100%;" border=0 />');
		  $('#calendarModal').modal();

	  });
      $('.addproject').on('click', function (e) {
            e.preventDefault();
            $.get('admin/usercreate.php')
                .done(function(data) {

                    $('#qAddProjectBody').html($(data).find('#addproject'));
                    $('#quickAddProjectModal').modal();

                    formModal();
             });
      });

      function formModal() {
        var $form = $('form[name="form"]');

        if(document.form && document.form.office_name) office_names();

        $('a[href*="useradmin"]').on('click', function(e) {
            e.preventDefault();
            $('#quickAddProjectModal').modal('hide');
        });
        $('a[href*="usercreate"]').on('click', function(e) {
            e.preventDefault();
            refresh();
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            var formdata = $form.serialize();
            $.post($form[0].action, formdata)
                .done(function(data) {
                    $('#qAddProjectBody').html($(data).find('#addproject'));
                    formModal();
                });
        });
      }


	  $('.report').on('click', function (e) {
		e.preventDefault();
//		console.log(JSON.stringify(rpoptions));
		if(typeof dispname !== 'undefined') {
		  $.ajax({
			url : 'reports/total_hours.php',
			method : 'post',
			data : rpoptions,
			success : function(data) {
			  $('#rpTitle').html(data.substring(data.indexOf('<div class=\'reportTitle'), data.indexOf('<!---RPTTITLEEND--->')));
			  $('#rpBody').html(data.substring(data.indexOf('<table class=\'reportBody'), data.indexOf('<!---RPTEND--->')));
			  $('#reportModal').modal();
			  window.clearInterval(r);
			}
		  });
		} else {
		  alert('Select a name first...');
		}
	  })
	  var c = setInterval('updateClock()', 1000);
	  $('body, .modal').on('click', function (event) {
		  window.clearInterval(r);
		  var target = $(event.target);
		  if(target.is("textarea")) r = setInterval('refresh()', <?=$refresh?>*1000*3);
		  else r = setInterval('refresh()', <?=$refresh?>*1000);
	  })
	  $.ajax({
		url : 'phpweather.php',
		success : function (data) {
		  $('#weather').html(data);
		}
	  })
	});

	Date.prototype.getSunday = function() {
	  var Sunday = this;
	  var d = Sunday.getDay();
	  if (d>0) Sunday.setDate(Sunday.getDate() - d);
	  return Sunday;
	}
	Date.prototype.getSaturday = function() {
	  var Saturday = this;
	  var d = Saturday.getDay();
	  if (d>0) Saturday.setDate(Saturday.getDate() + (6 - d));
	  return Saturday;
	}
	var d = new Date();
	d = d.getSunday();
	var dD = d.getDate();
	var dM = d.getMonth()+1;
	var dY = d.getYear()+1900;
	var sun = dM+"/"+dD+"/"+dY;
	var s = new Date();
	s = s.getSaturday();
	var sD = s.getDate();
	var sM = s.getMonth()+1;
	var sY = s.getYear()+1900;
	var sat = sM+"/"+sD+"/"+sY;


	var dispname;
	var rpoptions;

	function refresh() {
		var url = '/timeclock.php';
		window.location.href = url;
	}
	function updateClock() {
		var currentTime = new Date();
		var currentHours = currentTime.getHours();
		var currentMinutes = currentTime.getMinutes();
		var currentSeconds = currentTime.getSeconds();

		 // Pad the minutes and seconds with leading zeros, if required
		 currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
		currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

			// Choose either 'AM' or 'PM' as appropriate
		var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

		// Convert the hours component to 12-hour format if needed
		currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

		// Convert an hours component of '0' to '12'
		currentHours = ( currentHours == 0 ) ? 12 : currentHours;

		// Compose the string for display
		var currentTimeString = currentHours + ':' + currentMinutes + ':' + currentSeconds + ' ' + timeOfDay;
		$('#clock').html(currentTimeString);
	}
	function submitTime(status) {
		var form = $('form[name="timeclock"]');
		var tempElement = $("<input name='left_inout' value='"+status+"' type='hidden'/>");
		  // clone the IN button used to submit the form.
		tempElement.appendTo(form);
		$('#employee_pswd').removeAttr("disabled");
		$('form[name="timeclock"]').submit();
	}
	function clearModal(id) {
	  $(id).modal('hide');
	}
</script>

<?php
// set refresh rate for each page //

if ($refresh == "none") {
} else {
    echo "<script type='text/javascript'>var r = setInterval('refresh()', $refresh*1000);</script>\n";
}

if ($use_client_tz == "yes") {
    if (isset($_COOKIE['tzoffset'])) {
        $tzo = $_COOKIE['tzoffset'];
        settype($tzo, "integer");
        $tzo = $tzo * 60;
    }
}
elseif ($use_server_tz == "yes") {
    $tzo = date('Z');
} else {
    $tzo = "1";
}
?>
