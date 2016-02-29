<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
?>
<title>James Kerslake's Devon-style bellringing site - DRL stats</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/league_banner.inc"); ?>

<!-- Sub menu -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if(date("m")<=4) {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".($latest_year-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".$latest_year."'>Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
	<div class='head_2'>DRL Statistics and Records</div>
  <div class="text">On these pages I have compiled some assorted records and statistics using the league tables and the results of the competitions which make up those tables. If there are any ideas or requests for further statistics let me know, either by email (<a href="mailto:webmaster[at]myconid[dot]co[dot]uk">by clicking here</a>) or in person, and I'll try my best to come up with the goods.</div>

	 <div class="head_3">Collected records</div>
	
<div style="height:250px; float:none;">
	<div id="table_container" style="width:912px">
		<div id="table_header">
			<div id="width_300px_head"><a href="drl_stats.php?firstat=1&amp;lastat=9" style="color:#fff">League records</a></div>
  		<div id="width_300px_head"><a href="drl_stats.php?firstat=10&amp;lastat=16" style="color:#fff">Competition records</a></div>
			<div id="width_300px_head"><a href="drl_stats.php?firstat=17&amp;lastat=26" style="color:#fff">Team records</a></div>
		</div>
		
		<div id="table_body">	
			<div id="width_300px" style="text-align:center">
				<a href="drl_stats.php?firstat=1&amp;lastat=9">All league records</a><br /> <!-- send firstat and lastat -->
		    <a href="drl_stats.php?firstat=1">League winners</a><br /> <!-- send firstat only -->
  		  <a href="drl_stats.php?firstat=2">Number of competitions in each season</a><br />
    		<a href="drl_stats.php?firstat=3">Number of teams entering each season</a><br />
		    Highest end of season ranking:<br />
				<a href="drl_stats.php?firstat=4">5 or more competitions</a><br />
				<a href="drl_stats.php?firstat=5">All-comers</a><br />
	    	<a href="drl_stats.php?firstat=6">Largest and smallest victory margins</a><br />
	  	  <a href="drl_stats.php?firstat=7">Multiple league winners</a><br />
  	  	<a href="drl_stats.php?firstat=8">Highest ranking point total</a><br />
				<a href="drl_stats.php?firstat=9">Best end of season fault difference</a>
			</div>
	
			<div id="width_300px" style="height:inherit;text-align:center">
				<a href="drl_stats.php?firstat=10&amp;lastat=16">All competition records</a><br />
		    <a href="drl_stats.php?firstat=10">Highest competition entry</a><br />
    		<a href="drl_stats.php?firstat=11">Lowest total fault score</a><br />
		    <a href="drl_stats.php?firstat=12">Lowest average fault score</a><br />
    		<a href="drl_stats.php?firstat=13">Highest total fault score</a><br />
		    <a href="drl_stats.php?firstat=14">Highest average fault score</a><br />
    		<a href="drl_stats.php?firstat=15">Largest victory margin</a><br />
				<a href="drl_stats.php?firstat=16">Smallest victory margin</a>
			</div>
			
			<div id="width_300px" style="height:inherit;text-align:center">
				<a href="drl_stats.php?firstat=17&amp;lastat=26">All team records</a><br />
				<a href="drl_stats.php?firstat=17">Most competitions in a season</a><br />
				<a href="drl_stats.php?firstat=18">All-time most competitions</a><br />
				<a href="drl_stats.php?firstat=19">Lowest fault score</a><br />
				<a href="drl_stats.php?firstat=20">Highest fault score</a><br />
				<a href="drl_stats.php?firstat=21">Highest fault score to win</a><br />
				<a href="drl_stats.php?firstat=22">Lowest fault score to place last</a><br />
				<a href="drl_stats.php?firstat=23">Lowest all-time total fault score</a><br />
				<a href="drl_stats.php?firstat=24">All-time lowest average fault score</a><br />
				<a href="drl_stats.php?firstat=25">Best all-time fault difference</a><br />
				<a href="drl_stats.php?firstat=26">Disqualifications and No Results</a>
			</div>
		</div>
	</div>
</div>	
<div style="width:100%; float:none">
	<div class="head_3">Team statistics</div>
<?php include("access_console.php"); ?>
	<div class="head_3">Competition statistics</div>
	<div class="text">The drop down menu below gives access to the statistics for each competition. The competition page gives the all-time stats and then the individual event averages, plus a link to each individual event's results.</div>
	<form method='get' action='drl_comps.php' name='comp_details' style='text-align:center'>
<div style='text-align:center'>
<?php
	include("../allpages/dbinfo.inc");
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
	$query="SELECT * FROM DRL_competitions";
	$result=mysqli_query($cxn,$query);
	echo("<select name='comp' id='comp'>\n");
	echo("	<option value=''>Select a competition</option>\n");
	while($row = mysqli_fetch_assoc($result)) { 
		echo("	<option value='$row[competition]'>$row[competition]</option>\n");
	}
	echo("</select>\n");
?>
<input type='submit' value='Go!' />
</div>
</form>
</div>
</div>
</body>
</html>