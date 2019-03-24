<script type="text/javascript">

onload=function(){ attachHandlers(); }

function attachHandlers(){
  var the_nums = document.getElementsByName("number");
  for (var i=0; i < the_nums.length; i++) { the_nums[i].onclick=inputNumbers; }
}

function inputNumbers() {
  var the_field = document.getElementById('employee_pswd');
  var the_value = this.value;
  switch (the_value) {
    case 'CLR' :
      the_field.value = '';
      break;
	case 'C' :
	  the_field.value = the_field.value.slice(0, -1);
	break;
    default : document.getElementById("employee_pswd").value += the_value;
      break;
  }
  document.getElementById('employee_pswd').focus();
  return true;
}

</script>

<div id="keypad">
    <div class="row">
     <div class="col-xs-4"><input type="button" name="number" value="7" id="_7" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="8" id="_8" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="9" id="_9" class="btn btn-info btn-block"></div>
    </div>
    <div class="row">
     <div class="col-xs-4"><input type="button" name="number" value="4" id="_4" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="5" id="_5" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="6" id="_6" class="btn btn-info btn-block"></div>
    </div>
    <div class="row">
     <div class="col-xs-4"><input type="button" name="number" value="1" id="_1" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="2" id="_2" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="3" id="_3" class="btn btn-info btn-block"></div>
    </div>
    <div class="row">
	 <div class="col-xs-4"><input type="button" name="number" value="C" id="c_btn" class="btn btn-warning btn-block"></div>
	 <div class="col-xs-4"><input type="button" name="number" value="0" id="_0" class="btn btn-info btn-block"></div>
     <div class="col-xs-4"><input type="button" name="number" value="CLR" id="clr_btn" class="btn btn-danger btn-block"></div>
    </div>
</div>