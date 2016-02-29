<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
?>
<title>James Kerslake's Devon-style bellringing site - DRL archive</title>
<script language="javascript">
function reload(form)
{
var val=form.year.options[form.year.options.selectedIndex].value;
self.location='drl_archive.php?year=' + val ;
}
</script>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/league_banner.inc"); ?>

<!-- Sub menu -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if(date("m")<=4) {
	echo "<div id='sub_selected'><a class='sub_unselect' href='drl_tables.php?season=".($latest_year-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_selected'><a class='sub_unselect' href='drl_tables.php?season=".$latest_year."'>Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
	<div class='head_2'>DRL Archive</div>
	<div class='text'>The two sets of drop down menus below will take you into the archive. The results archive gives the results of the individual competitions used to calculate the tables found in the tables archive, if that makes sense...</div>
	<div class='head_4'>Results archive</div>
	<div class='text'><span class='bold'>Note:</span> The results given do not give 'A' and 'B' sections. In the cases where there were seperate 'A' and 'B' sections the first team in the latter are placed immediately after the last team in the former section</div>
	<div style='margin: 5px; text-align:center'>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
@$year=$_GET[year]; // Use this line or below line if register_global is off
if(strlen($year) > 0 and !is_numeric($year)){ // to check if $cat is numeric data or not. 
	
	echo "Data Error";
	exit;
}


// Get the data from db for first list box
$query=mysqli_query($cxn, "SELECT DISTINCT year FROM DRL_events WHERE year>=1986 ORDER BY year"); 

echo "	<form method='get' action='drl_results.php'>\n";

// Starting of first drop downlist
echo "		<select name='year' id='year' onchange=\"reload(this.form)\">
			<option value=''>Select a year</option>\n";

while($row = mysqli_fetch_assoc($query)) { 
	if($row[year]==@$year){
		echo "			<option selected value='$row[year]'>$row[year]</option>\n";
	} else {
		echo  "			<option value='$row[year]'>$row[year]</option>\n";
	}
}
echo "		</select>";

// Starting of second drop downlist

echo "		<select name='event_id' id='event_id'>\n";
if(isset($year) and strlen($year) > 0){
	$query=mysqli_query($cxn, "SELECT event_id, competition FROM DRL_events WHERE year=$year ORDER BY competition"); 
	echo "			<option value=''>Select a competition</option>\n";	
	while($row = mysqli_fetch_assoc($query)) { 
		echo  "			<option value='$row[event_id]'>$row[competition]</option>\n";
	}
} else {
	echo "			<option value=''>Select a year first</option>\n";
}
echo "		</select>\n";

//// Add your other form fields as needed here/////
echo "		<input type='submit' value='Go!'>\n";
echo "	</form>\n";
?>
	</div>

	<div class='head_4'>Tables archive</div>
	<div class='text'>The drop down list gives the seasons, simply select the year and click Go!</div>
	<div style='margin: 5px; text-align:center'>
<?php 
	// Get the data from table for the list box
	$result=mysqli_query($cxn, "SELECT DISTINCT year FROM DRL_events WHERE year>=1986 ORDER BY year"); 

	// form start
	echo "<form method='get' action='drl_tables.php'>\n";

	// First drop-down list
	echo "	<select name='season' id='season'>
		<option value=''>Select a year</option>\n";
	while($row = mysqli_fetch_assoc($result)) { 
		echo  "		<option value='$row[year]'>$row[year]</option>\n";
	}
	echo "	</select>\n";

	echo "	<input type='submit' value='Go!'>\n";
	echo "</form>\n";
?>
	</div>
</div>
</body>
</html>