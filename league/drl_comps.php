<?php
	/* competition statistics page - needs $_GET type input as follows
	 * comp - competition from database (will be inputted from drop down list)
	 */
	 
	/* ERROR CODES
	 * 101 - can't find all time stats
	 * 20x - can't find season averages
	 * 210 - can't find winning team
	 * 220 - can't find victory margin 
	 */
	 
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
	$month = date("m");
	$year = $latest_year;

// starting at the begining of the archive... 
	$year_loop = $earliest_year;
?>
<title>James Kerslake's Devon-style bellringing site - DRL competition stats</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/league_banner.inc"); ?>

<!-- Sub menu -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if($month<=4) {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".($year-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".$year."'>Full Table</a></div>";
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
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
// display Competition name
$query = "SELECT competition FROM DRL_competitions WHERE competition='$_GET[comp]'";
$result = mysqli_query($cxn, $query) or die ("Competition not found");
$comp_name = mysqli_fetch_assoc($result);

if($comp_name[competition] == NULL) { // Error check
	echo "<div class='head_2'>Error - Can't find the competition</div>
<div class='text' style='text-align:center'><a href='#'>Back to last</a></div>\n";
} else {
	echo "<div class='head_2'>Competition Statistics for the $comp_name[competition] competition</div>\n";

// ALL TIME STATISTICS FIRST
// get the data
	$query = "SELECT competition, SUM(tot_faults) AS tot_faults, SUM(tot_faults)/SUM(no_of_teams) AS avg_faults, COUNT(competition) AS no_of_events FROM DRL_event_overview WHERE competition='$comp_name[competition]' GROUP BY competition";//"SELECT * FROM DRL_event_overview WHERE team='$comp_name[comp]'";
	$result = mysqli_query($cxn, $query) or die ("101 error finding statistics");
	$all_time_stats = mysqli_fetch_assoc($result);
	
	$query = "SELECT avg(margin) AS avg_margin, competition FROM DRL_event_victory_margins WHERE competition='$comp_name[competition]' GROUP BY competition";
	$result = mysqli_query($cxn, $query) or die ("102 error finding statistics");
	$avg_margin = mysqli_fetch_assoc($result);

// build the table			
	echo "<div class='head_4'>All-time averages</div>\n";
// headers
	echo "<div style='height:80px; width:366px; margin:0 auto;'>\n<div id='table_header'>\n";
	echo "<div id='width_75px_head'>No of Events</div>\n";
	echo "<div id='width_100px_head'>Total<br />Faults</div>\n";
	echo "<div id='width_75px_head'>Average Faults</div>\n";
	echo "<div id='width_100px_head'>Average<br />Win Margin</div>\n";
	echo "</div>\n<div id='table_body'>\n";

// content
// number of no results
	echo "<div id='width_75px'>$all_time_stats[no_of_events]</div>\n";
// total faults
	echo "<div id='width_100px'>".number_format($all_time_stats[tot_faults], 2, '.', ',')."</div>\n";
// average faults
	printf("<div id='width_75px'>%.2f</div>\n", $all_time_stats[avg_faults]);
// average victory margin
	printf("<div id='width_100px'>%.2f</div>\n", $avg_margin[avg_margin]);
	echo "</div>\n</div>\n";

// set range to cover from the first season to the last season			
	$range=$all_time_stats[no_of_events];

// SEASONAL AVERAGES
	echo "<div class='head_4'>Seasonal averages</div>\n";
	
// flag for the table header
	$th_flag=0;

// loop until next year
	while ($year_loop <= $year+1) {

// query for the overview data and check that there is some
		$query = "SELECT * FROM DRL_event_overview WHERE competition='$comp_name[competition]' AND year=$year_loop";
		$result = mysqli_query($cxn, $query) or die ("error 20$th_flag finding statistics");
		$annual_stats = mysqli_fetch_assoc($result);
			
		$height = $range*22 + 48;
// table header
		if($th_flag==0) {
			echo "<div style='height:".$height."px; width:934px; margin:0 auto;'>\n<div id='table_header'>\n";
			echo "<div id='width_75px_head'>Season<br />&nbsp;</div>\n";
			echo "<div id='width_200px_head'>Location<br />&nbsp;</div>\n";
			echo "<div id='width_75px_head'>No of<br/>Teams</div>\n";
//			echo "<div id='width_75px_head'>Average Position</div>\n";
			echo "<div id='width_100px_head'>Total<br />Faults</div>\n";
			echo "<div id='width_75px_head'>Average Faults</div>\n";
			echo "<div id='width_200px_head'>Winning<br />Team</div>\n";
			echo "<div id='width_75px_head'>Victory<br />Margin</div>\n";
			echo "<div id='width_100px_head'>Links to <br />Results</div>\n";
//			echo "<div id='width_75px_head'>No Results</div>\n";
//			echo "</div>\n<div id='table_body'>\n";
			$th_flag=1;
		}

		if($annual_stats[competition] != NULL) {
				
// get the remaining data
			$query = "SELECT team, faults FROM DRL_event_winners WHERE event_id='$annual_stats[event_id]'";
			$result = mysqli_query($cxn, $query) or die ("210 error finding statistics");
			$winners = mysqli_fetch_assoc($result);

			$query = "SELECT margin, competition, year FROM DRL_event_victory_margins WHERE competition='$comp_name[competition]' AND year=$year_loop";
			$result = mysqli_query($cxn, $query) or die ("220 error finding statistics");
			$margin = mysqli_fetch_assoc($result);

// SEASONAL AVERAGES
// display averages for the season
// content
// season range (first season to last season)
			echo "<div id='width_75px' style='text-align:center'>$annual_stats[year]</div>\n";
// location
			echo "<div id='width_200px'>$annual_stats[location]</div>\n";
// no of teams
			echo "<div id='width_75px'>$annual_stats[no_of_teams]</div>\n";
// total faults
			echo "<div id='width_100px'>".number_format($annual_stats[tot_faults], 2, '.', ',')."</div>\n";
// average faults
			printf("<div id='width_75px'>%.2f</div>\n", $annual_stats[ave_faults]);
// Winning team
			echo "<div id='width_200px'>$winners[team]</div>\n";
// victory margin
			echo "<div id='width_75px'>$margin[margin]</div>\n";
// links to results
			echo "<div id='width_100px'><a href='/league/drl_results.php?year=$annual_stats[year]&event_id=$annual_stats[event_id]'>$annual_stats[year] results</a></div>\n";
// number of no results
//				if($start_year>2006) {
//					echo "<div id='width_75px'>$no_res[NR]</div>\n";
//				} else {
//					echo "<div id='width_75px'>N/A</div>\n";
//				}
		} 
		
// end stats loop
		$year_loop++;
	}
		echo "</div>\n</div>\n";
}
?>	
</div>
</body>
</html>
