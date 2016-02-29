<?php 
include("../allpages/dbinfo.inc");
include("drl_stat_queries.php");
include("../allpages/variables.php");

	$cxn = mysqli_connect($host, $user, $passwd, $database);
	$first_year = $earliest_year;
	if(date("m")<4) {
		$year_start=$latest_year-1;
	} else {
		$year_start=$latest_year;
	}
	if(date("m")<11) {
		$year_end=$latest_year-1;
	} else {
		$year_end=$latest_year;
	}
	if(date("m")<5) {
		$year_special=$latest_year-1;
	} else {
		$year_special=$latest_year;
	}
	$this_year = $latest_year;
	
	switch($_GET['stat']) {
	// league records
		case 1: // league winners
			$stat_title = "League Winners";
			$table_array=league_winners($first_year, $year_end);
			$num_of_cols = 3;
			$column_headers = array("Year", "5 or more competitions", "All-comers");
			break;
		case 2: // multiple league winners - no used yet
			$stat_title = "Multiple league winners";
			break;
		case 3: // no of competitions per season
			$stat_title = "Number of competitions in each season";
			$num_of_cols = 2;
			$column_headers = array("Year", "Comps");
			$query = "SELECT year, COUNT(year) AS comps FROM DRL_events WHERE year>=$earliest_year GROUP BY year ORDER BY year DESC ";
			$result = mysqli_query($cxn, $query);
			$i = 0;
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['year'];
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">$row[comps]</span>";
				} else {
					$table_array[$i][1] = $row['comps'];
				}
				$i++;
			}

			break;
		case 4: // no of teams per season
			$stat_title = "Number of teams enetering each season";
			$num_of_cols = 3;
			$column_headers = array("Year", "5 or more", "All-comers");			
			$query = "SELECT DRL_03a_no_of_teams_over_5.year, DRL_03a_no_of_teams_over_5.no_of_teams AS five, DRL_03b_no_of_teams_all_comers.no_of_teams AS ac FROM DRL_03a_no_of_teams_over_5 INNER JOIN DRL_03b_no_of_teams_all_comers ON DRL_03a_no_of_teams_over_5.year = DRL_03b_no_of_teams_all_comers.year ORDER BY year DESC";
			$result = mysqli_query($cxn, $query);
			$i = 0;
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['year'];
				if($row['year']==$year_end) {
					$table_array[$i][1] = "<span class=\"center bold\">$row[five]</span>";
					$table_array[$i][2] = "<span class=\"center bold\">$row[ac]</span>";
				} else {
					$table_array[$i][1] = $row['five'];
					$table_array[$i][2] = $row['ac'];
				}
				$i++;
			}
	
			break;
		case 5: // highest ranking for 5+ teams
			$stat_title = "Highest end of season ranking<br>Teams ringing in 5 or more competitions";
			$num_of_cols = 2;
			$column_headers = array("Ranking", "Team and year");
			$sub_title = "Top 100 scores";
			if($this_year == $year_end) {
				$query = "SELECT ranking, team, year FROM DRL_points_and_ranks WHERE no_of_comps >=5 ORDER BY ranking DESC, year LIMIT 0,100";
			} else {
				$query = "SELECT ranking, team, year FROM DRL_points_and_ranks WHERE no_of_comps >=5 AND year < $this_year ORDER BY ranking DESC, year LIMIT 0,100";
			}
			$result = mysqli_query($cxn, $query);
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$table_array[$i][0] = number_format($row['ranking'],2);
				if($row['year']==$year_end) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row[team]).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row[team]).", $row[year]";
				}
				$i++;
			}
			break;
		case 6: // highest ranking for all-comers
			$stat_title = "Highest end of season ranking<br>All-comers";
			$num_of_cols = 2;
			$column_headers = array("Ranking", "Team and year");
			$sub_title = "Top 100 scores";
			if($this_year == $year_end) {
				$query = "SELECT ranking, team, year FROM DRL_points_and_ranks ORDER BY ranking DESC, year LIMIT 0,100";
			} else {
				$query = "SELECT ranking, team, year FROM DRL_points_and_ranks WHERE year < $this_year ORDER BY ranking DESC, year LIMIT 0,100";
			}
			$result = mysqli_query($cxn, $query);
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$table_array[$i][0] = number_format($row['ranking'],2);
				if($row['year']==$year_end) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row[team]).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row[team]).", $row[year]";
				}
				$i++;
			}
			break;
		case 7: // Victory margins by year - 5 or more
			$stat_title = "Victory margins for each year<br>5 or more competitions";
			$num_of_cols = 4;
			$column_headers = array("Year", "Margin", "Winner", "Runner up");
			$i=0;
			for($year_loop=$year_end;$year_loop>=$first_year;$year_loop--) {
				// get the top two from the league table
				$table_array[$i][0] = $year_loop;  
				$query = "SELECT team, ranking, fault_diff FROM DRL_all_time_table WHERE year=$year_loop AND no_of_comps>=5 LIMIT 0,2";
				$result = mysqli_query($cxn, $query);
				// winner
				$winner = mysqli_fetch_assoc($result);
				$winner['team'] = team_check($winner['team']);
				// runner up
				$runner_up = mysqli_fetch_assoc($result);
				$runner_up['team'] = team_check($runner_up['team']);
				
				// calculate the margin for this year
				$victory_margin = number_format(($winner['ranking']-$runner_up['ranking']),2);
				if($year_loop == $year_end) {
					$table_array[$i][1] = "<span class=\"bold\">$victory_margin</span>";
				} else {
					$table_array[$i][1] = $victory_margin;
				}
				// store the winners and runners up
				$table_array[$i][2] = "$winner[team] <span class=\"italic small\">(Rank: ".number_format($winner['ranking'],2).", FD: ".number_format($winner['fault_diff'],2).")</span>";
				$table_array[$i][3] = "$runner_up[team] <span class=\"italic small\">(Rank: ".number_format($runner_up['ranking'],2).", FD: ".number_format($runner_up['fault_diff'],2).")</span>";
				$i++;
			}
			break;
		case 39: // victory margins - allcomers
			$stat_title = "Victory margins for each year<br>All comers";
			$num_of_cols = 4;
			$column_headers = array("Year", "Margin", "Winner", "Runner up");
			$i=0;
			for($year_loop=$year_end;$year_loop>=$first_year;$year_loop--) {
				// get the top two from the league table
				$table_array[$i][0] = $year_loop;  
				$query = "SELECT team, ranking, fault_diff FROM DRL_all_time_table WHERE year=$year_loop LIMIT 0,2";
				$result = mysqli_query($cxn, $query);
				// winner
				$winner = mysqli_fetch_assoc($result);
				$winner['team'] = team_check($winner['team']);
				// runner up
				$runner_up = mysqli_fetch_assoc($result);
				$runner_up['team'] = team_check($runner_up['team']);
				
				// calculate the margin for this year
				$victory_margin = number_format(($winner['ranking']-$runner_up['ranking']),2);
				if($year_loop == $year_end) {
					$table_array[$i][1] = "<span class=\"bold\">$victory_margin</span>";
				} else {
					$table_array[$i][1] = $victory_margin;
				}
				// store the winners and runners up
				$table_array[$i][2] = "$winner[team] <span class=\"italic small\">(Rank: ".number_format($winner['ranking'],2).", FD: ".number_format($winner['fault_diff'],2).")</span>";
				$table_array[$i][3] = "$runner_up[team] <span class=\"italic small\">(Rank: ".number_format($runner_up['ranking'],2).", FD: ".number_format($runner_up['fault_diff'],2).")</span>";
				$i++;
			}
			break;
		case 8: // Highest points total
			$stat_title = "Highest ranking point total";
			$sub_title = "Top 100 scores";
			$num_of_cols = 2;
			$column_headers = array("Points", "Team and year");
			$i = 0;
			if($this_year == $year_end) {
				$query = "SELECT tot_points, team, year FROM DRL_points_and_ranks ORDER BY tot_points DESC, year LIMIT 0,100";
			} else {
				$query = "SELECT tot_points, team, year FROM DRL_points_and_ranks WHERE year < $this_year ORDER BY tot_points DESC, year LIMIT 0,100";
			}
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['tot_points'];
				if($row['year']==$year_end) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']).", $row[year]";
				}
				$i++;
			}
			break;
		case 9: // best end of year fault diff
			$stat_title = "Best end of season fault difference";
			$sub_title = "Top 100 scores";
			$num_of_cols = 2;
			$column_headers = array("Fault diff", "Team and year");
			$i = 0;
			if($this_year == $year_end) {
				$query = "SELECT fault_diff, team, year FROM DRL_annual_fault_diff ORDER BY fault_diff DESC, year LIMIT 0,100";
			} else {
				$query = "SELECT fault_diff, team, year FROM DRL_annual_fault_diff WHERE year < $this_year ORDER BY fault_diff DESC, year LIMIT 0,100";
			}
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['fault_diff'],2);
				if($row['year']==$year_end) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row[team]).", $row[year]</span>";
				} else {
					$table_array[$i][1] =  team_check($row[team]).", $row[year]";
				}
				$i++;
			}
			break;
	// competition records
		case 10: // highest entry in a single event
			$stat_title = "Highest entry in a single event";
			$sub_title = "Top 100 scores";
			$num_of_cols = 2;
			$column_headers = array("Entry", "Competition");
			$i = 0;
			$query = "SELECT no_of_teams, competition, year, location FROM DRL_event_overview ORDER BY no_of_teams DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['no_of_teams'];
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$i++;
			}
			break;
		case 11: // lowest total faults
			$stat_title = "Lowest total fault score for a single competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Tot faults", "Competition", "Entry");
			$i = 0;
			$query = "SELECT tot_faults, competition, year, location, no_of_teams FROM DRL_event_overview ORDER BY tot_faults, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row[tot_faults],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$table_array[$i][2] = $row[no_of_teams];
				$i++; 
			}
			break;
		case 12: // lowest average faults
			$stat_title = "Lowest average fault score for a single competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 2;
			$column_headers = array("Ave faults", "Competition");
			$i = 0;
			$query = "SELECT ave_faults, competition, year, location, no_of_teams FROM DRL_event_overview
ORDER BY ave_faults, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row[ave_faults],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$i++;
			}
			break;
		case 13: // highest total faults
			$stat_title = "Highest total fault score for a single competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Tot Faults", "Competition", "Entry");
			$i = 0;
			$query = "SELECT tot_faults, competition, year, location, no_of_teams FROM DRL_event_overview ORDER BY tot_faults DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row[tot_faults],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$table_array[$i][2] = $row[no_of_teams];
				$i++; 
			}
			break;
		case 14: // highest average faults
			$stat_title = "Highest average fault score for a single competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 2;
			$column_headers = array("Ave Faults", "Competition");
			$i = 0;
			$query = "SELECT ave_faults, competition, year, location, no_of_teams FROM DRL_event_overview
ORDER BY ave_faults DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row[ave_faults],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$i++;
			}
			break;
		case 15: // largest victory margin
			$stat_title = "Largest victory margin in a single competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Margin", "Competition", "Winners and runners up");
			$i = 0;
			$query = "SELECT * FROM DRL_event_victory_margins ORDER BY margin DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row[margin],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$table_array[$i][2] = "<span class=\"italic\">".team_check($row['winners'])." ($row[win_faults]) beat ".team_check($row['runner_up'])." ($row[ru_faults])</span>";
;
				$i++;
			}
			break;
		case 16: // smallest victory margin
			$stat_title = "Smallest victory margin in a single competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Margin", "Competition", "Winners and runners up");
			$i = 0;
			$query = "SELECT * FROM DRL_event_victory_margins ORDER BY margin, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row[margin],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				}
				$table_array[$i][2] = "<span class=\"italic\">".team_check($row['winners'])." ($row[win_faults]) beat ".team_check($row['runner_up'])." ($row[ru_faults])</span>";
;
				$i++;
			}
			break;
	// team records
		case 17: // most comps in a season
			$stat_title = "Most competitions in a season";
			$sub_title = "Top 100 scores";
			$num_of_cols = 2;
			$column_headers = array("Comps", "Team and year");
			$i = 0;
			$query = "SELECT no_of_comps, team, year FROM DRL_teams_annual_overview ORDER BY no_of_comps DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['no_of_comps'];
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']).", $row[year]";
				}
				$i++;
			}
			break;
		case 18: // all time most comps
			$stat_title = "All-time most competitions";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Comps", "Team", "Years active");
			$i = 0;
			$query = "SELECT DRL_17_team_comps_per_season.team, SUM(DRL_17_team_comps_per_season.no_of_comps) AS comps, DRL_year_ranges.last_year, DRL_year_ranges.first_year FROM DRL_17_team_comps_per_season INNER JOIN DRL_year_ranges ON DRL_17_team_comps_per_season.team = DRL_year_ranges.team GROUP BY team ORDER BY comps DESC LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['comps'];
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row[team]);
				}
				$table_array[$i][2] = $row['first_year']." - ".$row['last_year'];
				$i++;
			}
			break;
		case 19: // lowest single fault score
			$stat_title = "Lowest fault scores";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Faults", "Team", "Competition");
			$i = 0;
			$query = "SELECT DRL_results.faults, DRL_results.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_results INNER JOIN DRL_events ON DRL_results.event_id = DRL_events.event_id WHERE DRL_results.faults<>0 ORDER BY DRL_results.faults ASC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['faults'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "<span class=\"italic\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				$i++;
			}
			break;
		case 20: // highest single fault score
			$stat_title = "Highest fault scores";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Faults", "Team", "Competition");
			$i = 0;
			$query = "SELECT DRL_results.faults, DRL_results.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_results INNER JOIN DRL_events ON DRL_results.event_id = DRL_events.event_id WHERE DRL_results.faults<>0 ORDER BY DRL_results.faults DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['faults'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "<span class=\"italic\">".loc_check($row['competition'], $row['location'], $row['year']).", $row[year]</span>";
				$i++;
			}
			break;
		case 21: // lowest annual average score 
			$stat_title = "Lowest average fault score for a single season";
			$sub_title = "Top 100 scores, 5 or more competitions in a season";
			$num_of_cols = 3;
			$column_headers = array("Ave", "Team and year", "Comps");
			$i = 0;
			$query = "SELECT avg_faults, team, year, no_of_comps FROM DRL_teams_annual_overview WHERE no_of_comps >=5 ORDER BY avg_faults, no_of_comps, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['avg_faults'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']).", $row[year]";
				}
				$table_array[$i][2] = $row['no_of_comps'];
				$i++;
			}
			break;
		case 22: // highest annual average score
			$stat_title = "Highest average fault score for a single season";
			$sub_title = "Top 100 scores, 5 or more competitions in a season";
			$num_of_cols = 3;
			$column_headers = array("Ave", "Team and year", "Comps");
			$i = 0;
			$query = "SELECT avg_faults, team, year, no_of_comps FROM DRL_teams_annual_overview WHERE no_of_comps >=5 ORDER BY avg_faults DESC, no_of_comps, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['avg_faults'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']).", $row[year]";
				}
				$table_array[$i][2] = $row['no_of_comps'];
				$i++;
			}
			break;
		case 23: // lowest all-time fault score
			$stat_title = "Lowest all-time total fault score";
			$sub_title = "Top 25 scores, 20 or more competitions";
			$num_of_cols = 4;
			$column_headers = array("Faults", "Team", "Years active", "Comps");
			$i = 0;
			$query = "SELECT tot_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY tot_faults LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['tot_faults'], 2);
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$table_array[$i][3] = $row['no_of_comps'];
				$i++;
			}
			break;
		case 24: // highest all-time fault score
			$stat_title = "Highest all-time total fault score";
			$sub_title = "Top 25 scores, 20 or more competitions";
			$num_of_cols = 4;
			$column_headers = array("Faults", "Team", "Years active", "Comps");
			$i = 0;
			$query = "SELECT tot_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY tot_faults DESC LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['tot_faults'], 2);
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$table_array[$i][3] = $row['no_of_comps'];
				$i++;
			}
			break;
		case 25: // lowest all-time average score
			$stat_title = "Lowest all-time average fault score";
			$sub_title = "Top 25 scores, 20 or more competitions";
			$num_of_cols = 4;
			$column_headers = array("Avg", "Team", "Years active", "Comps");
			$i = 0;
			$query = "SELECT avg_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY avg_faults LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['avg_faults'],2);
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$table_array[$i][3] = $row['no_of_comps'];				
				$i++;
			}
			break;
		case 26: // highest all-time average score
			$stat_title = "Highest all-time average fault score";
			$sub_title = "Top 25 scores, 20 or more competitions";
			$num_of_cols = 4;
			$column_headers = array("Avg", "Team", "Years active", "Comps");
			$i = 0;
			$query = "SELECT avg_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY avg_faults DESC LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['avg_faults'],2);
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$table_array[$i][3] = $row['no_of_comps'];				
				$i++;
			}
			break;
		case 27: // highest fault score to win a competition
			$stat_title = "Highest fault score to win a competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Faults", "Team", "Competition");
			$i = 0;
			$query = "SELECT DRL_event_winners.faults, DRL_event_winners.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_event_winners INNER JOIN DRL_events ON DRL_event_winners.event_id = DRL_events.event_id ORDER BY faults DESC, year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['faults'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				$i++;
			}
			break;
		case 28: // Lowest fault score to place last in a competition
			$stat_title = "Lowest fault score to come last in a competition";
			$sub_title = "Top 100 scores";
			$num_of_cols = 3;
			$column_headers = array("Faults", "Team", "Competition");
			$i = 0;
			$query = "SELECT DRL_results.faults, DRL_results.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_events INNER JOIN DRL_results ON DRL_events.event_id=DRL_results.event_id INNER JOIN DRL_event_overview ON DRL_events.event_id=DRL_event_overview.event_id WHERE DRL_results.faults <> 0 AND DRL_results.position = DRL_event_overview.no_of_teams ORDER BY DRL_results.faults, DRL_events.year LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['faults'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = loc_check($row['competition'], $row['location'], $row['year']).", $row[year]";
				$i++;
			}
			break;
		case 29: // Largest all-time fault difference
			$stat_title = "Largest all-time total fault difference";
			$sub_title = "Top 25 scores, 20 or more competitions";
			$num_of_cols = 3;
			$column_headers = array("Fault Diff", "Team", "Years Active");
			$i = 0;
			$query = "SELECT DRL_all_time_fault_diffs.fault_diff, DRL_all_time_fault_diffs.team, DRL_year_ranges.first_year, DRL_year_ranges.last_year FROM DRL_all_time_fault_diffs INNER JOIN DRL_year_ranges ON DRL_all_time_fault_diffs.team = DRL_year_ranges.team ORDER BY DRL_all_time_fault_diffs.fault_diff DESC LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['fault_diff'],2);
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$i++;
			}
			break;
		case 30: // No results
			$stat_title = "Disqualifications and No Results";
			$nrs_output = no_results($year_start, $first_year); 
			$i = 0;
			break;
	// ringer records
		case 31: // most competition wins
			$stat_title = "Most competition wins";
			$sub_title = "Top 25 ringers,<br>competitions included: Devon 8 Bell, Devon Major Final, Devon Minor Final, and North and South Devon Qualifiers";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 32: // most final wins
			$stat_title = "Most wins in a Devon final";
			$sub_title = "Top 25 ringers,<br>competitions included: Devon 8 Bell and Devon Major Final";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Devon 8 Bell' OR DRL_events.competition LIKE 'Major Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 33: // most 8 bell wins
			$stat_title = "Most wins in an eight bell competition";
			$sub_title = "Top 25 ringers,<br>competitions included: Devon 8 Bell only";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Devon 8 Bell' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 34: // most 6 bell wins
			$stat_title = "Most wins in a six bell competition";
			$sub_title = "Top 25 ringers,<br>competitions included: Devon Major Final, Devon Minor Final, and North and South Devon Qualifiers";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE '%Qualifier' OR DRL_events.competition LIKE '%Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 35: // most major final wins
			$stat_title = "Most wins at the Major Final";
			$sub_title = "Top 25 ringers<br>competitions included: Devon Major Final only";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Major Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 36: // most qualifier wins
			$stat_title = "Most wins in a Devon Qualifier";
			$sub_title = "Top 25 ringers,<br>competitions included: North and South Devon Qualifiers";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE '%Qualifier' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 37: // most wins on treble
			$stat_title = "Most wins ringing the treble";
			$sub_title = "Top 25 ringers,<br>competitions included: Devon 8 Bell, Devon Major Final, Devon Minor Final, and North and South Devon Qualifiers";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers_x_event.bell = 1 GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 38: // most wins on tenor
			$stat_title = "Most wins ringing the tenor";
			$sub_title = "Top 25 ringers,<br>competitions included: Devon 8 Bell, Devon Major Final, Devon Minor Final, and North and South Devon Qualifiers";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT IF(tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps,tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps, tenor_ringers_6_bell.comps) AS comps, tenor_ringers_6_bell.forename, tenor_ringers_6_bell.surname FROM tenor_ringers_6_bell LEFT JOIN tenor_ringers_8_bell ON tenor_ringers_8_bell.surname=tenor_ringers_6_bell.surname
UNION
 SELECT IF(tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps, tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps, tenor_ringers_8_bell.comps) as comps, tenor_ringers_8_bell.forename, tenor_ringers_8_bell.surname FROM tenor_ringers_8_bell LEFT JOIN tenor_ringers_6_bell ON tenor_ringers_8_bell.surname=tenor_ringers_6_bell.surname
ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 40: // most minor final wins
			$stat_title = "Most wins at the Minor Final";
			$sub_title = "Top 25 ringers<br>competitions included: Devon Minor Final only";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Ringer");
			$i = 0;
			$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname
FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Minor Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,25";
			$result = mysqli_query($cxn, $query);
			while($row=mysqli_fetch_assoc($result)) {
				$query2 = "SELECT DRL_events.year FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers.forename LIKE '$row[forename]' AND ringers.surname LIKE '$row[surname]' AND DRL_events.year = $year_special";
				$result2 = mysqli_query($cxn, $query2);
				$flag = mysqli_num_rows($result2);
				
				$table_array[$i][0] = $row['comps'];
				if($flag>=1) {
					$table_array[$i][1] = "<span class=\"bold\">$row[forename] $row[surname]</span>";
				} else {
					$table_array[$i][1] = "$row[forename] $row[surname]";
				}
				$i++;
			}
			break;
		case 41: // most competition wins in a season (team record)
			$stat_title = "Most competition wins in a season";
			$sub_title = "Top 100 teams";
			$num_of_cols = 2;
			$column_headers = array("Wins", "Team and year");
			$i = 0;
			$query = "SELECT COUNT(DRL_results.position) AS wins, DRL_results.team, DRL_events.year FROM DRL_events INNER JOIN DRL_results ON DRL_events.event_id = DRL_results.event_id WHERE DRL_results.position = 1 GROUP BY team, year ORDER BY wins DESC, year ASC LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['wins'];
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']).", $row[year]";
				}
				$i++;
			}
			break;
		case 42: // all-time number of wins (team record)
			$stat_title = "All time most competition wins";
			$sub_title = "Top 100 teams";
			$num_of_cols = 3;
			$column_headers = array("Wins", "Team", "Years active");
			$i = 0;
			$query = "SELECT COUNT(DRL_results.position) AS wins, DRL_teams_all_time_over_view.team, DRL_teams_all_time_over_view.first_year, DRL_teams_all_time_over_view.last_year FROM DRL_teams_all_time_over_view INNER JOIN DRL_results ON DRL_teams_all_time_over_view.team=DRL_results.team WHERE DRL_results.position = 1 GROUP BY team ORDER BY wins DESC, last_year DESC LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = $row['wins'];
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$i++;
			}
			break;
		case 43: // best win percentage by season (team record)
			$stat_title = "Highest win percentage in a season";
			$sub_title = "Top 100 teams, 5 or more competitions in a season";
			$num_of_cols = 2;
			$column_headers = array("Percentage", "Team and year");
			$i = 0;
			$query = "SELECT (DRL_event_wins.wins/DRL_teams_annual_overview.no_of_comps)*100 AS percentage, DRL_teams_annual_overview.team, DRL_teams_annual_overview.year FROM DRL_event_wins INNER JOIN DRL_teams_annual_overview ON DRL_event_wins.team = DRL_teams_annual_overview.team AND DRL_event_wins.year = DRL_teams_annual_overview.year WHERE DRL_teams_annual_overview.no_of_comps>5 GROUP BY team, year ORDER BY percentage DESC LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['percentage'],2);
				if($row['year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team']).", $row[year]</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']).", $row[year]";
				}
				$i++;
			}
			break;
		case 44: // all-time best win percentage (team record)
			$stat_title = "All-time best win percentages";
			$sub_title = "Top 100 teams, 20 or more competitions";
			$num_of_cols = 3;
			$column_headers = array("Percentage", "Team", "Years active");
			$i = 0;
			$query = "SELECT (SUM(DRL_event_wins.wins)/DRL_teams_all_time_over_view.no_of_comps)*100 AS percentage, DRL_teams_all_time_over_view.team, DRL_teams_all_time_over_view.first_year, DRL_teams_all_time_over_view.last_year FROM DRL_event_wins INNER JOIN DRL_teams_all_time_over_view ON  DRL_event_wins.team = DRL_teams_all_time_over_view.team WHERE DRL_teams_all_time_over_view.no_of_comps>20 GROUP BY team ORDER BY percentage DESC, last_year DESC LIMIT 0,100";
			$result = mysqli_query($cxn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$table_array[$i][0] = number_format($row['percentage'],2);
				if($row['last_year']==$year_start) {
					$table_array[$i][1] = "<span class=\"bold\">".team_check($row['team'])."</span>";
				} else {
					$table_array[$i][1] = team_check($row['team']);
				}
				$table_array[$i][2] = "$row[first_year] - $row[last_year]";
				$i++;
			}
			break;	
		default:
			$stat_title="Error-No code, no record!";
			$num_of_cols = 0;
			$column_headers = NULL;
			$table_array = NULL;
			break;
	} // end stat switch
?>
<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - DRL Records</title>
<script src="/allpages/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="/allpages/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new2.css">
<link type="text/css" rel="stylesheet" href="/allpages/jquery-ui-1.8.6.custom.css">

<script type="text/javascript">
</script>
<style type="text/css">
table {
	margin: 0 auto;
}
</style>
</head>

<body>

<!-- Banner at page top -->
<div id="top_banner">
<div id="top_left"></div>
<div id="top_center" class="head_1">Call change ringing resources</div>
<div id="top_right"></div>
</div>

<!-- Menu Banner -->
<div id="menu_banner">
<div id="menu_container">
<div id="ul_end"></div>
<div id="unselected_tab"><a class="unselected" href="/index.php">Home</a></div>
<div id="us_split"></div>
<div id="selected_tab"><a class="selected" href="/league/index.php">League</a></div>
<div id="su_split"></div>
<div id="unselected_tab"><a class="unselected" href="/ladder/index.html">Ladder</a></div>
<div id="uu_split"></div>
<div id="unselected_tab"><a class="unselected" href="/compositions/index.html">Compositions</a></div>
<div id="ur_end"></div>
</div>
</div>

<!-- Sub menu Banner -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
	echo "<div id='sub_selected'><a class='sub_unselect' href='drl_tables.php?season=".$year_start."'>Full Table</a></div>";
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<div style="background:white; padding-bottom:2px">
<h2>DRL records and statistics</h2>
<p class="center"><a href="javascript:history.go(-1);">Back to last page</a></p>
<h3><?php echo $stat_title; ?></h3>
<?php
	if(isset($_GET)) {
		$next_output = "<p class=\"small center\">";
		if(isset($sub_title)) {
			$next_output .= "$sub_title<br>\n";
		}
		switch($_GET['stat']) {
			case 1: case 4: case 5: case 6: case 7: case 8: case 9: case 39:
				$next_output .= "<span class=\"bold\">Bold</span> type indicates records set in $year_end";
				break;
			case 30:
				break;
			case 31: case 32: case 33: case 34: case 35: case 36: case 37: case 38:
				$next_output .= "<span class=\"bold\">Bold</span> type indicates a ringer who has won a competition in $year_special";
				break;
			default:
				$next_output .= "<span class=\"bold\">Bold</span> type indicates records set in $year_start";
				break;
		}

		echo "$next_output</p>\n";
		if($_GET['stat']==30) { 
			echo $nrs_output;
		} else {
			echo "<table>\n<tr>\n";
	
			foreach($column_headers as $title) {
				echo "<th>$title</th>\n";
			}
			echo "</tr>\n";
		
			for($i=0;$i<count($table_array); $i++) {
				if($table_array[$i][0] == "" || $table_array[$i][0] == NULL) {
					break;
				}
				$table_row = "<tr>\n";
				for($j=0;$j<$num_of_cols;$j++) {
					if($j==0) {
						$table_row .= "<td class=\"bold right\">".$table_array[$i][0]."</td>\n";
					} else {
						switch($_GET['stat']) {
							case 3:	case 4:
								$table_row .= "<td class=\"center\">".$table_array[$i][$j]."</td>\n";
								break;
							case 7: case 39:
								if($j==1) {
									$table_row .= "<td class=\"right\">".$table_array[$i][$j]."</td>\n";
								} else {
									$table_row .= "<td>".$table_array[$i][$j]."</td>\n";
								}
								break;
							case 11: case 13: case 18: case 21: case 22:
								if($j==2) {
									$table_row .= "<td class=\"center\">".$table_array[$i][$j]."</td>\n";
								} else {
									$table_row .= "<td>".$table_array[$i][$j]."</td>\n";
								}
								break;
							case 23: case 24: case 25: case 26: case 29:
								if($j==2) {
									$table_row .= "<td class=\"center\">".$table_array[$i][$j]."</td>\n";
								} else if($j==3) {
									$table_row .= "<td class=\"right\">".$table_array[$i][$j]."</td>\n";
								} else {
									$table_row .= "<td>".$table_array[$i][$j]."</td>\n";
								}
								break;
							default:
								$table_row .= "<td>".$table_array[$i][$j]."</td>\n";
								break;
						}
					}
				}
				$table_row .= "</tr>\n";
				echo $table_row;
			}	
			echo "</table>\n";
		}
		
		echo "<p class=\"center\"><a href=\"javascript:history.go(-1);\">Back to last page</a></p>\n";
	}
	
?>
</div>
</body>
</html>
