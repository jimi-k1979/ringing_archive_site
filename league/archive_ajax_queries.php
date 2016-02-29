<?php
	include ("../allpages/dbinfo.inc");
    include ("../allpages/variables.php");
	include("drl_stat_queries.php");
	$cxn = mysqli_connect($host, $user, $passwd, $database);
	$year_limit = $earliest_year;
	
	function results_by_event($event,$cxn) {
		echo "<reply>\n";
		$query = "SELECT competition, location, year FROM DRL_events WHERE event_id=$event";
		$result = mysqli_query ($cxn, $query);
		$row = mysqli_fetch_assoc($result);
		echo "<event>\n<year>$row[year]</year>\n<comp>$row[competition]</comp>\n<loc>$row[location]</loc>\n</event>";
		$query = "SELECT position, team, faults FROM DRL_results WHERE event_id = $event ORDER BY position";
		$result = mysqli_query ($cxn,$query);
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<result>\n";
			echo "<pos>$row[position]</pos>\n<team>$row[team]</team>\n<faults>$row[faults]</faults>\n";
			echo "</result>\n";
		}
		echo "</reply>\n";
	};
	
	// ringers queries
	if(isset($_POST['ring_flag'])) {
		if($_POST['year']=="0") { // get the years that the competitions were held
			switch($_POST['comp']) {
				case "8bell":
					$input_comp = "Devon 8 Bell";
					break;
				case "maj":
					$input_comp = "Major Final";
					break;
				case "min":
					$input_comp = "Minor Final";
					break;
				case "qualn":
					$input_comp = "North Devon Qualifier";
					break;
				case "quals":
					$input_comp = "South Devon Qualifier";
					break;
				case "sdev8":
					$input_comp = "South Devon 8 Bell";
					break;
				case "inter":
					$input_comp = "Interdeanery";
					break;
				default:
					echo "error!\n";
					return;
			}
			$query = "SELECT DISTINCT ringers_x_event.event_id, DRL_events.year FROM DRL_events INNER JOIN ringers_x_event ON DRL_events.event_id = ringers_x_event.event_id WHERE DRL_events.competition = '$input_comp' ORDER BY year DESC";
			$result = mysqli_query($cxn,$query)
					or die ("<reply><event><id></id><year>Critical database error</year></event></reply>");
			echo "<reply>\n";
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<event>\n";
				echo "<id>$row[event_id]</id>\n<year>$row[year]</year>\n";
				echo "</event>\n";
			}
			echo "</reply>\n";
			return;
		} else { // get the ringers and team
			$input_event = $_POST['year'];
			echo "<reply>\n";
			$query = "SELECT year, competition, location FROM DRL_events WHERE event_id = $input_event";
			$result = mysqli_query($cxn,$query);
			$row = mysqli_fetch_assoc($result);
			echo "<event>\n";
			echo "<year>$row[year]</year>\n<comp>$row[competition]</comp>\n<loc>$row[location]</loc>\n";
			$query = "SELECT team, faults FROM DRL_results WHERE event_id = $input_event AND position = 1";
			$result = mysqli_query ($cxn,$query);
			if(mysqli_num_rows($result)==0) {
				if($_POST['comp'] == "8bell") {
					switch ($row['year']) {
						case 1928:
							echo "<team>North Tawton</team>\n";
							break;
						default:
							echo "<team>Kingsteginton</team>\n";
							break;
					}
				} else {
					switch ($row['year']) {
						case 1925:
							echo "<team>North Tawton</team>\n";
							break;
						default:
							echo "<team>Plymouth, Eggbuckland</team>\n";
							break;
					}
				}
			} else {
				$row = mysqli_fetch_assoc($result);
				echo "<team>$row[team]</team>\n<faults>$row[faults]</faults>\n";
			}
			echo "</event>\n";
			
			$query = "SELECT ringers.forename, ringers.surname, ringers_x_event.bell FROM ringers INNER JOIN ringers_x_event ON ringers.ringer_id=ringers_x_event.ringer_id WHERE ringers_x_event.event_id=$input_event ORDER BY bell ASC";
			$result = mysqli_query ($cxn,$query);
			while ($row = mysqli_fetch_assoc($result)){
				echo "<ringer>\n";
				echo "<bell>$row[bell]</bell>\n<name>$row[forename] $row[surname]</name>\n";
				echo "</ringer>\n";
			}
			echo "</reply>\n";
		}
	}	
	
	// table query
	if(isset($_POST['allcomers'])) {
		$input_year = $_POST['season'];
		// get list of competitions
		$query = "SELECT competition, location FROM DRL_events WHERE year = $input_year";
		$result = mysqli_query ($cxn,$query);
		echo "<reply>\n";
		while ($row = mysqli_fetch_assoc($result)){
			echo "<event>\n";
			echo "<comp>$row[competition]</comp>\n<loc>$row[location]</loc>\n";
			echo "</event>\n";
		}
		// get table
		if($_POST['allcomers']==0){
			$query = "SELECT team, no_of_comps, ranking, fault_diff FROM DRL_all_time_table WHERE year=$input_year ORDER BY ranking DESC, fault_diff DESC";
		} else {
			$query = "SELECT team, no_of_comps, ranking, fault_diff FROM DRL_all_time_table WHERE year=$input_year AND no_of_comps >= 5 ORDER BY ranking DESC, fault_diff DESC";
		}
		$result = mysqli_query ($cxn,$query);
		while ($row = mysqli_fetch_assoc($result)){
			echo "<placing>\n";
			echo "<team>$row[team]</team>\n<comps>$row[no_of_comps]</comps>\n<rank>$row[ranking]</rank>\n<fd>$row[fault_diff]</fd>\n";
			echo "</placing>\n";
		}
		echo "</reply>\n";
	}
	
	// get competitions by year
	if(isset($_POST['decade']) && isset($_POST['year']) && isset($_POST['event'])) {
		if($_POST['event']=="0") {
			$input_year=$_POST['year'];
			$query = "SELECT event_id, competition FROM DRL_events WHERE year=$input_year ORDER BY competition";
			$result = mysqli_query ($cxn,$query) 
					or die ("<reply><event><id></id><comp>Critical database error</comp></event></reply>\n");
			echo "<reply>\n"; 
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<event>\n";
				echo "<id>$row[event_id]</id>\n<comp>$row[competition]</comp>\n";
				echo "</event>\n";
			}
			echo "</reply>";
			return;
		} else {
			results_by_event($_POST['event'],$cxn);
			return;
		}
	} 
	// get competitions by competition name
	if(isset($_POST['comp']) && isset($_POST['event'])) {
		if($_POST['event']=="0" && !isset($_POST['location'])) {
			$input_comp=$_POST['comp'];
			$query = "SELECT event_id, year FROM DRL_events WHERE competition = '$input_comp' AND year >= $year_limit ORDER BY year DESC";
			$result = mysqli_query ($cxn,$query)
					or die ("<reply><event><id></id><year>Critical database error</year></event></reply>\n");
			echo "<reply>\n";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<event>\n";
				echo "<id>$row[event_id]</id>\n<year>$row[year]</year>\n";
				echo "</event>\n";
			}
			echo "</reply>";
			return;
		} else if($_POST['comp']=="0") {
		// get competition by location 
			$input_loc=$_POST['location'];
			$query = "SELECT DISTINCT competition FROM DRL_events WHERE location='$input_loc' AND year >= $year_limit ORDER BY competition";
			$result = mysqli_query ($cxn,$query)
					or die ("<reply><comp><name>Critical database error</name></comp></reply>\n");
			echo "<reply>\n";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<comp>\n";
				echo "<name>$row[competition]</name>\n";
				echo "</comp>\n";
			}
			echo "</reply>\n";
			return;
		} else if($_POST['event']=="0") { // get year by location and competition
			$input_comp=$_POST['comp'];
			$input_loc=$_POST['location'];
			$query = "SELECT event_id, year FROM DRL_events WHERE competition='$input_comp' AND year >= $year_limit AND location='$input_loc' ORDER BY year";
			$result = mysqli_query ($cxn,$query)
					or die ("<reply><event><id></id><year>Critical database error</year></event></reply>\n");
			echo "<reply>\n";
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<event>\n";
				echo "<id>$row[event_id]</id>\n<year>$row[year]</year>\n";
				echo "</event>\n";
			}
			echo "</reply>\n";
			return;
		} else {
			results_by_event($_POST['event'],$cxn);
			return;
		}
	}
	// queries for stat search
	if(isset($_POST['tg'])) {
		$query="SELECT team FROM teams WHERE team LIKE ";
		switch($_POST['tg']) {
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
		echo "<reply>\n";	
		while($row = mysqli_fetch_assoc($result)) { 
			echo  "<team>$row[team]</team>\n";
		}
		echo "</reply>\n";
	}
	// TEAM STAT SEARCH RESULTS FUNCTION
	if(isset($_POST['team']) && isset($_POST['res']) && isset($_POST['range']) && isset($_POST['start']) && isset($_POST['adv'])) {
		$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
		// display team name and deanery
		$query = "SELECT team, deanery FROM teams WHERE team LIKE '$_POST[team]'";
		$result = mysqli_query($cxn, $query) or die ("team not found");
		$team_details = mysqli_fetch_assoc($result);

		if($team_details[team] == NULL) { // Error check
			echo "<reply>\n<error>Error - Can't find the team</error>\n</reply>\n";
			return;
		} else {
			echo "<?xml version=\"1.0\" ?>\n<reply>\n<team>$team_details[team]</team>\n";
			echo "<deanery>$team_details[deanery]</deanery>\n";
			$start_year = $_POST['start'];
			$end_year = $_POST['start'] + $_POST['range'] - 1;
			$adv = $_POST['adv'];
			if($_POST['res']!=1) {
				if($_POST['range']==0) { // if there is more than one season display all-time averages first
					echo "<all-time-stats>\n";
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

					// content
					// season range (first season to last season)
					echo "<year_range>$all_time_stats[first_year] - $all_time_stats[last_year]</year_range>\n";
					// total competitions
					echo "<comps>$all_time_stats[no_of_comps]</comps>\n";
					// average ranking
					echo "<ave_rank>".number_format($avg_ranking['average'],2)."</ave_rank>\n";
					// average position
					echo "<ave_pos>".number_format($all_time_stats['avg_pos'],2)."</ave_pos>\n";
					// total faults
					echo "<tot_faults>".number_format($all_time_stats['tot_faults'],2)."</tot_faults>\n";
					// average faults
					echo "<ave_faults>".number_format($all_time_stats['avg_faults'],2)."</ave_faults>\n";
					// all time fault difference
					echo "<fault_diff>".number_format($fd['fault_diff'],2)."</fault_diff>\n";
					// total points
					echo "<tot_points>$all_time_stats[tot_points]</tot_points>\n";
					// average points (not the same as average ranking)
					echo "<ave_points>".number_format($all_time_stats['avg_points'],2)."</ave_points>\n";
					// number of no results
					echo "<nr>$no_res[NR]</nr>\n";
				
					// set range to cover from the first season to the last season			
					$range=$all_time_stats[last_year]-$all_time_stats[first_year]+1;
					$start_year=$all_time_stats[first_year];
					echo "</all-time-stats>\n";
				} // end all time stats
		
				// if the advanced options are switched off don't display the seasonal averages.
				if($adv!=0) {
					// work out which columns are needed
					for($i=7; $i>-1; $i--) {
						$adv_compare = pow(2,$i);
						if($adv>=$adv_compare) {
							$col_flag[$i] = 1;
							$adv -= $adv_compare;
						} else {
							$col_flag[$i] = 0;
						}
					}
					// reset $adv
					$adv = $_POST[adv];
					echo "<seasonal-stats>\n";
					
					for($season_loop=0; $season_loop<$range; $season_loop++) {
						// query for the overview data and check that there is some
						$query = "SELECT * FROM DRL_teams_annual_overview WHERE team='$team_details[team]' AND year=$start_year";
						$result = mysqli_query($cxn, $query) or die ("error 20$season_loop finding statistics");
						$annual_stats = mysqli_fetch_assoc($result);					
						
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
							echo "<season>\n";
							echo "<year>$annual_stats[year]</year>\n";
							
							for($i=0; $i<8; $i++) {
								if($col_flag[$i]==1) {
								switch($i) {
									case 0: // total competitions
										echo "<no_of_comps>$annual_stats[no_of_comps]</no_of_comps>\n";
										break;
									case 3: // Ranking
										echo "<ranking>".number_format($annual_stats['avg_points'],2)."</ranking>\n";
										break;
									case 2: // average position
										echo "<ave_pos>".number_format($annual_stats['avg_pos'],2)."</ave_pos>\n";
										break;
									case 1: // total faults
										echo "<tot_faults>".number_format($annual_stats['tot_faults'],2)."</tot_faults>\n";
										break;
									case 4: // average faults
										echo "<ave_faults>".number_format($annual_stats['avg_faults'],2)."</ave_faults>\n";
										break;
									case 7: // fault difference
										echo "<fault_diff>".number_format($fd['fault_diff'], 2)."</fault_diff>\n";
										break;
									case 6: // total points
										echo "<tot_points>$annual_stats[tot_points]</tot_points>\n";
										break;
									case 5: // number of no results
										echo "<nr>$no_res[NR]</nr>\n";
										break;
									default: // shouldn't get here - die horribly
										echo "<error>MAJOR ERROR!<error>";
										break;
								} // end switch
								} // end if $col_flag[$i]==1
							}// end advanced options loop
							echo "</season>\n";
							
						} // end if $annual_stats != NULL
						$start_year++;
						
					} // end season loop
					echo "</seasonal-stats>\n";
					
				} // end if adv!=0
				if($_GET[range]!=0 && $adv==0) {
					echo "<error>No statistics selected!</error>\n";
				} // end if range is dodgy
			} // end stats
	
			// if all results are required loop over them
			if($_POST['res']!=0) {
				echo "<results>\n";
				if($_POST['range']==0) {
					$start_year = $_POST['start'];
					$end_year = $latest_year;
				} else {
					$start_year = $_POST['start']; // reset start year
					$end_year = $_POST['start'] + $_POST['range'] - 1; // and end year
				}
	
				$query = "SELECT DRL_events.year, DRL_events.competition, DRL_events.location, DRL_results.position, DRL_results.faults, DRL_results.points FROM DRL_events INNER JOIN DRL_results ON DRL_events.event_id = DRL_results.event_id WHERE DRL_results.team='$team_details[team]' AND DRL_events.year>=$start_year AND DRL_events.year<=$end_year ORDER BY year ASC";

				$result = mysqli_query($cxn, $query);
				while ($row = mysqli_fetch_assoc($result)) {
					echo "<competition>\n";
					// competition name and year
					// display location if different
					echo "<event>".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</event>\n";
					// position (if no result put NR rather than a number)
					// faults - don't display if no result
					if($row['faults']<0.1) {
						echo "<position>No result</position>\n<faults>N/A</faults>\n";
					} else {
						// out of (can be calculated from the points - quicker/easier than lookup?)
						if($row['points'] % 1 == 0) { // even number
							$out_of = $row['points']/2 + $row[position];
						} else { // odd number
							$out_of = ($row[points]+1)/2 + $row[position];
						}
						echo "<position>$row[position] out of $out_of</position>\n<faults>".number_format($row['faults'],2)."</faults>\n";
					}
					// points awarded
					echo "<points>$row[points]</points>\n";
// end results loop
					echo "</competition>\n";
				}	
				echo "</results>\n";
			}
		echo "</reply>\n";
		}
	} 
	// competition stat search
	if(isset($_POST['comp_search'])) {
	// display Competition name
		$query = "SELECT competition FROM DRL_competitions WHERE competition='$_POST[comp_search]'";
		$result = mysqli_query($cxn, $query) or die ("Competition not found");
		$comp_name = mysqli_fetch_assoc($result);

		if($comp_name[competition] == NULL) { // Error check
			echo "<reply><message>Error - Can't find the competition</message></reply>";
		} else {
			echo "<?xml version=\"1.0\" ?>\n<reply>\n<competition>$comp_name[competition]</competition>\n";

			// ALL TIME STATISTICS FIRST
			// get the data
			$query = "SELECT competition, SUM(tot_faults) AS tot_faults, SUM(tot_faults)/SUM(no_of_teams) AS avg_faults, COUNT(competition) AS no_of_events FROM DRL_event_overview WHERE competition='$comp_name[competition]' GROUP BY competition";//"SELECT * FROM DRL_event_overview WHERE team='$comp_name[comp]'";
			$result = mysqli_query($cxn, $query);
			$all_time_stats = mysqli_fetch_assoc($result);
	
			$query = "SELECT avg(margin) AS avg_margin, competition FROM DRL_event_victory_margins WHERE competition='$comp_name[competition]' GROUP BY competition";
			$result = mysqli_query($cxn, $query);
			$avg_margin = mysqli_fetch_assoc($result);

			// build the reply			
			echo "<all-time-stats>\n";
			// content
			// number of no events
			echo "<events>$all_time_stats[no_of_events]</events>\n";
			// total faults
			echo "<tot_faults>".number_format($all_time_stats['tot_faults'], 2)."</tot_faults>\n";
			// average faults
			echo "<ave_faults>".number_format($all_time_stats['avg_faults'], 2)."</ave_faults>\n";
			// average victory margin
			echo "<ave_margin>".number_format($avg_margin['avg_margin'], 2)."</ave_margin>\n";
			echo "</all-time-stats>\n";


			// SEASONAL AVERAGES
			echo "<seasonal-stats>\n";
	
			// query for the overview data and check that there is some
			$query = "SELECT * FROM DRL_event_overview WHERE competition='$comp_name[competition]' ORDER BY year DESC";
			$comp_list = mysqli_query($cxn, $query);
			while($annual_stats = mysqli_fetch_assoc($comp_list)) {
				
				// build reply
				echo "<season>\n";
				// get the remaining data
				$query = "SELECT team, faults FROM DRL_event_winners WHERE event_id='$annual_stats[event_id]'";
				$result = mysqli_query($cxn, $query);
				$winners = mysqli_fetch_assoc($result);
	
				$query = "SELECT margin, competition, year FROM DRL_event_victory_margins WHERE competition LIKE '$comp_name[competition]' AND year=$annual_stats[year]";
				$result = mysqli_query($cxn, $query);
				$margin = mysqli_fetch_assoc($result);
	
				// display averages for the season
				// content
				// season range (most recent first)
				echo "<year>$annual_stats[year]</year>\n";
				// location
				echo "<location>$annual_stats[location]</location>\n";
				// no of teams
				echo "<no_of_teams>$annual_stats[no_of_teams]</no_of_teams>\n";
				// total faults
				echo "<tot_faults>".number_format($annual_stats['tot_faults'], 2)."</tot_faults>\n";
				// average faults
				echo "<ave_faults>".number_format($annual_stats['ave_faults'], 2)."</ave_faults>\n";
				// Winning team
				echo "<winner>$winners[team]</winner>\n";
				// victory margin
				echo "<margin>$margin[margin]</margin>\n";
				// links to results
				echo "<id>$annual_stats[event_id]</id>\n";
				echo "</season>\n";
			}
			
			echo "</seasonal-stats>\n</reply>\n";
		}
	}
?>