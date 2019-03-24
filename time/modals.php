<!-- Modal -->
<div class="modal fade" id="clockinModal" tabindex="-1" role="dialog" aria-labelledby="clockinModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h2 class="modal-title text-center text-success">Hello!</h2>
	  </div>
	  <div class="modal-body text-left" style="margin-left: 20%">
		<h4>You are clocked in.</h4>Have a great day!
		<?php// include 'includes/dailycomic.php' ?>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal" >OK</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="lunchModal" tabindex="-1" role="dialog" aria-labelledby="lunchModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h1 class="modal-title text-center"><span class="glyphicon glyphicon-cutlery"></span>&nbsp;&nbsp; Lunch! &nbsp;&nbsp;<span class="glyphicon glyphicon-cutlery"></span></h1>
	  </div>
	  <div class="modal-body  text-center">
		Please remember to punch back in when you're done!
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal" >OK</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="clockoutModal" tabindex="-1" role="dialog" aria-labelledby="clockoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h3 class='alert alert-primary text-center text-primary modal-title'>Good Bye!</h3>
	  </div>
	  <div class="modal-body text-center">You are clocked out.</div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="dupeModal" tabindex="-1" role="dialog" aria-labelledby="dupeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h3 class='alert alert-warning text-center modal-title'>You've already clocked <?=($inout === 'Lunch')? 'out for ' . $inout : $inout ?>.</h3>
	  </div>
	  <div class="modal-body text-center"><span class="glyphicon glyphicon-question-sign"></span> Did you need something fixed?  See the boss.</div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="wrongpassModal" tabindex="-1" role="dialog" aria-labelledby="wrongpassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h3 class='alert alert-danger text-center modal-title'>Sorry, you entered the wrong password!</h3>
	  </div>
	  <div class="modal-body text-center">Please try again.</div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="projectsModal" tabindex="-1" role="dialog" aria-labelledby="projectsModalLabel" aria-hidden="true"> </div>

<!-- Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 id="rpTitle" class='modal-title text-center'> </h4>
	  </div>
	  <div id="rpBody" class="modal-body"> </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="r = setInterval('refresh()', <?=$refresh?>*1000);
">Close</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 100%; height: 100%;">
	<div class="modal-content" style="width: 100%; height: 100%;">
	  <div class="modal-header">
		<button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Close</button>
		<h4 id="calTitle" class='modal-title text-center'> </h4>
	  </div>
	  <div id="calBody" class="modal-body" style="padding: 0; height: 90%;"> </div>
	</div>
  </div>
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="quickAddProjectModal" tabindex="-1" role="dialog" aria-labelledby="quickAddProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div id="qAddProjectBody" class="modal-body" style="padding: 0; height: 90%;"> </div>
	</div>
  </div>
</div><!-- /.modal -->



