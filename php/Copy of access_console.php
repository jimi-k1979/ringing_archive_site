<!-- code for an improved stats access panel -->
<script language='javascript'>
// drop down list controller
function reload(form)
{
var val=form.tg.options[form.tg.options.selectedIndex].value;
self.location='access_console.php?tg=' + val ;
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

/* client side controls for year range section
 * if the all time button is selected grey out and don't use the text boxes 
 * else use text boxes to send the info to the next page
 */
function enable_fields() {
	document.team_details.start.disabled=false;
	document.team_details.start.value=2003;
	document.team_details.end.disabled=false;
	document.team_details.end.value=<?php echo date("Y"); ?>;
}

function disable_fields() {
	document.team_details.start.disabled=true;
	document.team_details.end.disabled=true;
}

function enable_stats() {
  for(var i=0; i<document.team_details.advanced.length; i++) {
		document.team_details.advanced[i].disabled=false;
	}
}

function disable_stats() {
  for(var i=0; i<document.team_details.advanced.length; i++) {
		document.team_details.advanced[i].disabled=true;
	}
}

// function for controlling whether the advanced options box is visible or not
function toggle(element) {
	if(document.getElementById(element).style.display=="none") {
		document.getElementById(element).style.display = "";
	} else {
		document.getElementById(element).style.display = "none";
		select_all();
	}
}

// select all and clear all functions
function clear_all() {
	for(var i=0; i<document.team_details.advanced.length; i++) {
		document.team_details.advanced[i].checked = "";
	}
}

function select_all() {
	for(var i=0; i<document.team_details.advanced.length; i++) {
		document.team_details.advanced[i].checked = "checked";
	}
}

// function to check and submit the form
function check_form (form) {
	var team; // team name
	var res = 2; // stats or results flag
	var range = 0; // year range
	var adv = 0; // advanced options
	var start_year; // start year
	
	// check that there is a team to submit
	if(form.team.value=="") {
		window.alert("You need to select a team");
		return false;
	}
	
	// first check form for errors in text boxes:
	// - Both boxes must be numbers greater than 2003 and end should be greater than or equal to start
	// - Neither year can be in the future
	// work out the range for the php output
	for(i=form.range.length-1; i>-1; i--) { // Are the text boxes in use?
		if(form.range[i].checked) {
			flag=i;
			i=-1;
		}
	}
	if(flag == -1) { // error of no radio button checked - shouldn't get here!
		window.alert("Error - no year button selected!");
		return false;

	} else if(flag == 1) { // text boxes are in use...
		start_year = parseFloat(form.start.value);
		end_year = parseFloat(form.end.value);
		
		if (isNaN(start_year) || isNaN(end_year)) { // cheeck for non numerical input
			window.alert("Please use numbers only");
			return false;
		} else if (start_year><?php echo date("Y"); ?> || end_year><?php echo date("Y"); ?>) { // is either year in the future?
			window.alert("Neither year can be in the future");
			return false;
		} else if (start_year<2003 || end_year<start_year) { // check that the numbers are in the right range
			window.alert("The start year must be 2003 or later\nand the end year must not be before the end year");
			return false;
		} else { // years fall in the limits, set the range
			range = end_year-start_year+1;
		}
	} else { // text boxes are not in use, set the range to 0 for all-time
		range = 0;
	}
	
	// Collate the various part needed for submission - the team, the range, the start year (if necessary),
	// stats or results, advanced options
	// team name
	team = form.team.value;
		
	// result or stats or both?
	for(var i=form.res.length-1; i>-1; i--) {
		if(form.res[i].checked) {
			res=i;
			i=-1;
		}
	}
	if(res == -1) { // error of no buttons checked - shouldn't get here
		window.alert("Error - no res button checked!");
		return false;
	}
	
	// advanced options section
	for(i=0; i<form.advanced.length; i++) {
		if(form.advanced[i].checked) {
			adv+=Math.pow(2,i);
		}
	}

	// submit the details to the drl_team.php page - the unsecure way at the moment
	window.location='drl_team.php?team='+team+'&res='+res+'&range='+range+'&start='+start_year+'&adv='+adv;
}
</script>

<div class='text'>Using the form below you can select to view the statistics and/or results for every season from 2003 for any tower that has entered a competition since then. The towers are ordered as they would be in 'Dove' so, for example, you'll find Eggbuckland under 'P' for Plymouth and Alphington under 'E' for Exeter.</div>
<?php	
include("../allpages/dbinfo.inc");
$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
@$tg=$_GET[tg]; // Use this line or below line if register_global is off
if(strlen($tg) > 0 and !is_numeric($tg)){ // to check if $cat is numeric data or not. 
	echo "Data Error";
	exit;
}
?>
<form method='get' action='drl_team.php' name='team_details' style='text-align:center'>
<div style='text-align:center'>
<?php
echo "<select name='tg' id='tg' onchange='reload(this.form)'>\n";
echo "<option value=''>Select a group</option>\n";
if ($tg==1) echo "<option selected value='1'>A-E</option>\n"; else echo "<option value='1'>A-E</option>\n";
if ($tg==2) echo "<option selected value='2'>F-J</option>\n"; else echo "<option value='2'>F-J</option>\n";
if ($tg==3) echo "<option selected value='3'>K-O</option>\n"; else echo "<option value='3'>K-O</option>\n";
if ($tg==4) echo "<option selected value='4'>P-T</option>\n"; else echo "<option value='4'>P-T</option>\n";
if ($tg==5) echo "<option selected value='5'>U-Z</option>\n"; else echo "<option value='5'>U-Z</option>\n";
echo "</select>\n";

echo "<select name='team' id='team'>\n";
if(isset($tg) and strlen($tg) > 0){
	$query="SELECT team FROM teams WHERE team LIKE ";
	switch($tg) {
		case 1:
			$query.="'A%' OR team LIKE 'B%' OR team LIKE 'C%' OR team LIKE 'D%' OR team LIKE 'E%'";
			break;
		case 2:
			$query.="'F%' OR team LIKE 'G%' OR team LIKE 'H%' OR team LIKE 'I%' OR team LIKE 'J%'";
			break;
		case 3:
			$query.="'K%' OR team LIKE 'L%' OR team LIKE 'M%' OR team LIKE 'N%' OR team LIKE 'O%'";
			break;
		case 4:
			$query.="'P%' OR team LIKE 'Q%' OR team LIKE 'R%' OR team LIKE 'S%' OR team LIKE 'T%'";
			break;
		case 5:
			$query.="'U%' OR team LIKE 'V%' OR team LIKE 'W%' OR team LIKE 'X%' OR team LIKE 'Y%' OR team LIKE 'Z%'";
			break;
	}
	
	$result=mysqli_query($cxn, $query); 
	echo "<option value=''>Select a team</option>\n";	
	while($row = mysqli_fetch_assoc($result)) { 
		echo  "			<option value='$row[team]'>$row[team]</option>\n";
	}
} else {
	echo "			<option value=''>Select letter group first</option>\n";
}
echo "		</select>\n";
?>
</div>
	<div style='text-align:center'>
		<label>
			<input name='res' type='radio' value='0' onClick='enable_stats()' /> Statistics only
		</label>
		<label>
			<input name='res' type='radio' value='1' onClick='disable_stats()' /> Results only
		</label>
		<label>
			<input name='res' type='radio' value='2' checked='checked' onClick='enable_stats()' /> Both statistics and results
		</label>
	</div>
	<div style='text-align:center'>
		<label>
			<input name='range' type='radio' value='0' checked='checked' onClick='disable_fields()' /> All-time
		</label>
		<label>
			<input name='range' type='radio' value='1' onClick='enable_fields()' /> Year range -
		</label> 
		<label>
			Start year: <input name='start' type='text' style='width:50px' maxlength='4' disabled='true' />
		</label>
		<label>
			&nbsp;End year: <input name='end' type='text' style='width:50px' maxlength='4' disabled='true' /> 
		</label>
	</div>
	
	<div style='text-align:center'>
		<fieldset style='width:432px; margin:0 auto'>
			<legend><a href='javascript:toggle("stat_options")'>Advanced options (click to toggle)</a></legend>
			<div id='stat_options' style='display:none'>
				<div style='text-align:center'>Select which stats you want to display using the options below<br /><a href='javascript:clear_all("this.form.advanced")'>Clear all</a> | <a href='javascript:select_all("this.form.advanced")'>Select all</a></div>
				<div style='width:140px; margin:2px; float:left; text-align:left'>
					<label>
						<input name='advanced' type='checkbox' checked='checked' /> Competitions
					</label><br />
					<label>
						<input name='advanced' type='checkbox' checked='checked' /> Total faults
					</label><br />
					<label>
						<input name='advanced' type='checkbox' checked='checked' /> Average position
					</label>
				</div>
				<div style='width:140px; margin:2px; float:left; text-align:left'>
					<label>
						<input name='advanced' type='checkbox' checked='checked' /> Ranking
					</label><br />
					<label>
						<input name='advanced' type='checkbox' checked='checked' /> Average faults
					</label><br />
					<label>
						<input name='advanced' type='checkbox' checked='checked' /> No Results
					</label>
				</div>
				<div style='width:140px; margin:2px; float:left; text-align:left'>
					<label>
						<input name='advanced' type='checkbox'  checked='checked' /> Total points
					</label>
					<label><br />
						<input name='advanced' type='checkbox' checked='checked' /> Fault difference
					</label>
				</div>
			</div>
		</fieldset>
	</div>
	<div style='text-align:center'>
		<input type='button' value='Go!' onClick='check_form(this.form);' />
	</div>
</form>