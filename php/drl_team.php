<?php
	/* team statistics page - needs $_GET type input as follows
	 * team - team from database (will be inputted from drop down lists)
	 * range - flag for all time or specific season or range of seasons
	 *    0 is all time - change after invocation
	 *		1 is single season, next variable used to define the season
	 *		any other number is the number of seasons in the range starting from the next variable
	 * start - first season
	 * res - flag for whether all results are shown
	 *		0 is no results, just averages
	 *		1 is just results, no averages
	 *		2 is all results and averages
	 * adv - changes exactly which stats are displayed
	 */
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
	$month = date("m");
	$year = date("Y");
?>
<title>James Kerslake's Devon-style bellringing site - DRL team stats</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/league_banner.inc"); ?>

<!-- Sub menu -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:560px;">
<div id="sub_unselected"><a class="sub_unselect" href="../php/index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if($month<=4) {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".($year-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".$year."'>Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="../php/drl_archive.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="../php/drl_stat_home.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="../php/rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
// display team name and deanery
$query = "SELECT team, deanery FROM teams WHERE team='$_GET[team]'";
$result = mysqli_query($cxn, $query) or die ("team not found");
$team_details = mysqli_fetch_assoc($result);

$start_year = $_GET[start];
$end_year = $_GET[end];
$range = $_GET[range];
$adv = $_GET[adv];

if($team_details[team] == NULL) { // Error check
	echo "<div class='head_2'>Error - Can't find the team</div>
<div class='text' style='text-align:center'><a href='#'>Back to last</a></div>\n";
} else {
	echo "<div class='head_2'>Team Statistics for $team_details[team]</div>\n";
	echo "<div class='head_3'>$team_details[deanery] Deanery</div>\n";
	
	if($_GET[res]!=1) {
		if($range==0) {
// if there is more than one season display all-time averages first

// get the data
			$query = "SELECT * FROM DRL_teams_all_time_over_view WHERE team='$team_details[team]'";
			$result = mysqli_query($cxn, $query) or die ("101 error finding statistics");
			$all_time_stats = mysqli_fetch_assoc($result);
			
			$query = "SELECT avg(ranking) AS average FROM DRL_all_time_table WHERE team='$team_details[team]'";
			$result = mysqli_query($cxn, $query) or die ("102 error finding statistics");
			$avg_ranking = mysqli_fetch_assoc($result);
			
			$query = "SELECT fault_diff FROM DRL_all_time_fault_diffs WHERE team='$team_details[team]'";
			$result = mysqli_query($cxn, $query) or die ("103 error finding statistics");
			$fd = mysqli_fetch_assoc($result);

			$query = "SELECT count(team) AS NR FROM DRL_no_results WHERE team='$team_details[team]'";
			$result = mysqli_query($cxn, $query) or die ("104 error finding statistics");
			$no_res = mysqli_fetch_assoc($result);

// build the table			
			echo "<div class='head_4'>All-time averages</div>\n";
// headers
			echo "<div style='height:80px; width:960px; margin:0 auto;'>\n<div id='table_header'>\n";
			echo "<div id='width_100px_head'>Years competed</div>\n";
			echo "<div id='width_100px_head' style='width:120px'>Competitions<br />&nbsp;</div>\n";
			echo "<div id='width_75px_head'>Average Ranking</div>\n";
			echo "<div id='width_75px_head'>Average Position</div>\n";
			echo "<div id='width_100px_head'>Total<br />Faults</div>\n";
			echo "<div id='width_75px_head'>Average Faults</div>\n";
			echo "<div id='width_150px_head'>All-time Fault Difference</div>\n";
			echo "<div id='width_75px_head'>Total Points</div>\n";
			echo "<div id='width_75px_head'>Average Points</div>\n";
			echo "<div id='width_75px_head'>Total No Results</div>\n";
			echo "</div>\n<div id='table_body'>\n";

// content
// season range (first season to last season)
			echo "<div id='width_100px' style='text-align:center'>$all_time_stats[first_year] - $all_time_stats[last_year]</div>\n";
// total competitions
			echo "<div id='width_100px' style='text-align:center; width:120px'>$all_time_stats[no_of_comps]</div>\n";
// average ranking
			printf("<div id='width_75px'>%.2f</div>\n", $avg_ranking[average]);
// average position
			printf("<div id='width_75px'>%.2f</div>\n", $all_time_stats[avg_pos]);
// total faults
			echo "<div id='width_100px'>".number_format($all_time_stats[tot_faults], 2, '.', ',')."</div>\n";
// average faults
			printf("<div id='width_75px'>%.2f</div>\n", $all_time_stats[avg_faults]);
// all time fault difference
			echo "<div id='width_150px' style='text-align:right'>".number_format($fd[fault_diff], 2, '.', ',')."</div>\n";
// total points
			echo "<div id='width_75px'>$all_time_stats[tot_points]</div>\n";
// average points (not the same as average ranking)
			printf("<div id='width_75px'>%.2f</div>\n", $all_time_stats[avg_points]);
// number of no results
			echo "<div id='width_75px'>$no_res[NR]</div>\n";
			
			echo "</div>\n</div>\n";
// set range to cover from the first season to the last season			
			$range=$all_time_stats[last_year]-$all_time_stats[first_year]+1;
			$start_year=$all_time_stats[first_year];
		}
		
		// if the advanced options are switched off don't display the seasonal averages.
		if($adv!=0) {
			echo "<div class='head_4'>Seasonal averages</div>\n";
			for($season_loop=0; $season_loop<$range; $season_loop++) {
		
	// query for the overview data and check that there is some
				$query = "SELECT * FROM DRL_teams_annual_overview WHERE team='$team_details[team]' AND year=$start_year";
				$result = mysqli_query($cxn, $query) or die ("error 20$season_loop finding statistics");
				$annual_stats = mysqli_fetch_assoc($result);
	//table dimensions		
				$height = $range*22 + 48;
				$tot_width = 79;
	// table header
				if($season_loop==0) {
			
					// work out which columns are needed
					for($i=7; $i>-1; $i--) {
						$adv_compare = pow(2,$i);
						if($adv>=$adv_compare) {
							$col_flag[$i] = 1;
							$adv -= $adv_compare;
							if($i==1 || $i==7 || $i==0) {
								$head_class[$i] = "100px";
								if($i==0) {
									$tot_width += 124;
								} else {
									$tot_width += 104;
								}
							} else {
								$head_class[$i] = "75px";
								$tot_width += 79;
							}
						} else {
							$col_flag[$i] = 0;
						}
					}
					// reset $adv
					$adv = $_GET[adv];
								
					// display the required columns
					echo "<div style='height:".$height."px; width:".$tot_width."px; margin:0 auto;'>\n<div id='table_header'>\n";
					echo "<div id='width_75px_head'>Season<br />&nbsp;</div>\n";
					for($i=0; $i<8; $i++) {
					  if($col_flag[$i]==1) {
							switch($i) {
								case 0:
									echo "<div id='width_100px_head' style='width:120px'>Competitions<br />&nbsp;</div>\n";
									break;
								case 3:
									echo "<div id='width_75px_head'>Ranking<br/>&nbsp;</div>\n";
									break;
								case 2:
									echo "<div id='width_75px_head'>Average Position</div>\n";
									break;
								case 1:
									echo "<div id='width_100px_head'>Total<br />Faults</div>\n";
									break;
								case 4:
									echo "<div id='width_75px_head'>Average Faults</div>\n";
									break;
								case 7:
									echo "<div id='width_100px_head'>Fault Difference</div>\n";
									break;
								case 6:
									echo "<div id='width_75px_head'>Total Points</div>\n";
									break;
								case 5:
									echo "<div id='width_75px_head'>No Results</div>\n";
									break;
								default:
									// should never get here - crash horribly!
									echo "<div class='head_2'>MAJOR ERROR!</div>";
									break;
							}
						}
					}
					echo "</div>\n<div id='table_body'>\n";
				}

				if($annual_stats[no_of_comps] != NULL) {
	// get the remaining data
					$query = "SELECT fault_diff FROM DRL_annual_fault_diff WHERE team='$team_details[team]' AND year=$start_year";
					$result = mysqli_query($cxn, $query) or die ("21$season_loop error finding statistics");
					$fd = mysqli_fetch_assoc($result);

					$query = "SELECT count(team) AS NR FROM DRL_no_results WHERE team='$team_details[team]' AND year=$start_year";
					$result = mysqli_query($cxn, $query) or die ("22$season_loop error finding statistics");
					$no_res = mysqli_fetch_assoc($result);

	// SEASONAL AVERAGES
	// display averages for the season
	// content
	// season range (first season to last season)
					echo "<div id='width_75px' style='text-align:center'>$annual_stats[year]</div>\n";
					for($i=0; $i<8; $i++) {
						if($col_flag[$i]==1) {
							switch($i) {
								case 0: // total competitions
									echo "<div id='width_100px' style='text-align:center; width:120px'>$annual_stats[no_of_comps]</div>\n";
									break;
								case 3: // Ranking
									printf("<div id='width_75px'>%.2f</div>\n", $annual_stats[avg_points]);
									break;
								case 2: // average position
									printf("<div id='width_75px'>%.2f</div>\n", $annual_stats[avg_pos]);
									break;
								case 1: // total faults
									echo "<div id='width_100px'>".number_format($annual_stats[tot_faults], 2, '.', ',')."</div>\n";
									break;
								case 4: // average faults
									printf("<div id='width_75px'>%.2f</div>\n", $annual_stats[avg_faults]);
									break;
								case 7: // all time fault difference
									echo "<div id='width_100px' style='text-align:right'>".number_format($fd[fault_diff], 2, '.', ',')."</div>\n";
									break;
								case 6: // total points
									echo "<div id='width_75px'>$annual_stats[tot_points]</div>\n";
									break;
								case 5: // number of no results
									if($start_year>2006) {
										echo "<div id='width_75px'>$no_res[NR]</div>\n";
									} else {
										echo "<div id='width_75px'>N/A</div>\n";
									}
									break;
								default: // shouldn't get here - die horribly
									echo "<div class='head_2'>MAJOR ERROR!</div>";
									break;
							}
						}
					}
				}
	// end stats loop
				$start_year++;
			}
			echo "</div>\n</div>\n";
		}
		if($_GET[range]!=0 && $adv==0) {
			echo "<div class='head_3'>No statistics selected!</div>\n";
		}
	}
// if all results are required loop over them
	if($_GET[res]!=0) {
		echo "<div class='head_4'>Results</div>\n";
		if($_GET[range]==0) {
			$start_year = 2003;
			$end_year = $year;
		} else {
			$start_year = $_GET[start]; // reset start year
			$end_year = $start_year + $range - 1;
		}
	
		$query = "SELECT DRL_events.year, DRL_events.competition, DRL_events.location, DRL_results.position, DRL_results.faults, DRL_results.points FROM DRL_events INNER JOIN DRL_results ON DRL_events.event_id = DRL_results.event_id WHERE DRL_results.team='$team_details[team]' AND DRL_events.year>=$start_year ORDER BY DRL_events.year";
		$result = mysqli_query($cxn, $query);
		$row = mysqli_fetch_assoc($result);
// table headers
		echo "<div style='width:816px; margin:0 auto'>\n<div id='table_head'>\n";
		echo "<div id='width_500px_head'>Competition</div>\n";
		echo "<div id='width_150px_head'>Position</div>\n";
		echo "<div id='width_75px_head'>Faults</div>\n";
		echo "<div id='width_75px_head'>Points</div>\n";
		echo "</div>\n<div id='table_body'>\n";
		
// display results
		while($row[year]<=$end_year) {
// competition name and year
// display location if different
			switch($row[competition]) {
				case "Newton Abbot, Ipplepen and Torbay Deaneries":
					$name = "NAIT Deaneries";
					break;
				case "Plympton and Ivybridge Deaneries":
					$name = "P&amp;I Deaneries";
					break;
				default:
					$name = $row[competition];
			}
			
			echo "<div id='width_500px'>$name $row[year]";

			if(strcmp($row[competition],$row[location])==0 ||
				$row[competition] == "Eggbuckland" ||
				$row[competition] == "Kilkhampton 8 Bell" ||
				$row[competition] == "Kilkhampton 6 Bell" ||
				$row[competition] == "Stratton 8 Bell" ||
				$row[competition] == "Stratton 6 Bell") {
					echo "</div>\n";
			} else {
					echo ", held at $row[location]</div>\n";
			}
// position (if no result put NR rather than a number)
			if($row[faults]<0.1) {
				echo "<div id='width_150px' style='text-align:center'>No result</div>\n";
			} else {
// out of (can be calculated from the points - quicker/easier than lookup?)
				if($row[points] % 1 == 0) { // even number
					$out_of = $row[points]/2 + $row[position];
				} else { // odd number
					$out_of = ($row[points]+1)/2 + $row[position];
				}
				echo "<div id='width_150px' style='text-align:center'>$row[position] out of $out_of</div>\n";
			}
// faults - don't display if no result
			if($row[faults]<0.1) {
				echo "<div id='width_75px'>N/A</div>\n";
			} else {
				echo "<div id='width_75px'>$row[faults]</div>\n";
			}
// points awarded
			echo "<div id='width_75px'>$row[points]</div>\n";
// end results loop
			$row = mysqli_fetch_assoc($result);
			if($row[year]==NULL) break;
		}	
		echo "&nbsp;</div>\n</div>\n";
	}
}
?>	
</div>
</body>
</html>
