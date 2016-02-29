<?php
include ("../allpages/dbinfo.inc");
include ("../allpages/variables.php");
include ("drl_stat_queries.php");
$cxn = mysqli_connect($host, $user, $passwd, $database);

$this_year=$latest_year;	
if(date("m")<4) {
	$year_start=$this_year-1;
} else {
	$year_start=$this_year;
}
if(date("m")<11) {
	$year_end=$this_year-1;
} else {
	$year_end=$this_year;
}
if(date("m")<5) {
	$year_comp=$this_year-1;
} else {
	$year_comp=$this_year;
}
$first_year = $earliest_year;

echo "<?xml version=\"1.0\"?>\n<reply>\n";
echo "<id>$_POST[stat_no]</id>\n";

// the missing numbers are those that have to be built into stat_home. These are 2, 7, 30 and 39.
switch ($_POST['stat_no']) {
	case 1:
		$query = "SELECT DRL_winners_over_5.year, DRL_winners_over_5.team AS five, DRL_winners_all_comers.team AS ac FROM DRL_winners_over_5 INNER JOIN DRL_winners_all_comers ON DRL_winners_over_5.year = DRL_winners_all_comers.year WHERE DRL_winners_over_5.year<=".$year_end." AND DRL_winners_over_5.year>=".($year_end-10)." ORDER BY year DESC";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Year</name>\n<name>5 or more competitions</name>\n<name>All-comers</name>\n</headers>\n";
		$last = array ('year' => 1900, 'five' => "", 'ac' => "");
		while($row = mysqli_fetch_assoc($result)) {
			if($row['year']!=$last['year']) {
				echo "<row>\n<year>$row[year]</year>\n<five>$row[five]</five>\n<ac>$row[ac]</ac>\n</row>\n";
				$last = $row;
			}
		}
		echo "</reply>\n";
		break;
	case 3:
		$query = "SELECT year, COUNT(year) AS comps FROM DRL_events GROUP BY year ORDER BY year DESC LIMIT 0,11";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Year</name>\n<name>Competitions</name>\n</headers>\n";
		while($row = mysqli_fetch_assoc($result)) {
			if($row['year']<=$year_end) {
				echo "<row>\n<year>$row[year]</year>\n<comps>$row[comps]</comps>\n</row>\n";
			}
		}
		echo "</reply>\n";
		break;
	case 4:
		$query = "SELECT DRL_03a_no_of_teams_over_5.year, DRL_03a_no_of_teams_over_5.no_of_teams AS five, DRL_03b_no_of_teams_all_comers.no_of_teams AS ac FROM DRL_03a_no_of_teams_over_5 INNER JOIN DRL_03b_no_of_teams_all_comers ON DRL_03a_no_of_teams_over_5.year = DRL_03b_no_of_teams_all_comers.year ORDER BY year DESC LIMIT 0,11";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Year</name>\n<name>5 or more competitions</name>\n<name>All-comers</name>\n</headers>\n";
		while($row = mysqli_fetch_assoc($result)) {
			if($row['year']<=$year_end) {
				echo "<row>\n<year>$row[year]</year>\n<five>$row[five]</five>\n<ac>$row[ac]</ac>\n</row>\n";
			}
		}
		echo "</reply>\n";
		break;
	case 5:
		if($this_year == $year_end) {
			$query = "SELECT ranking, team, year FROM DRL_points_and_ranks WHERE no_of_comps >=5 ORDER BY ranking DESC, year LIMIT 0,10";
		} else {
			$query = "SELECT ranking, team, year FROM DRL_points_and_ranks WHERE no_of_comps >=5 AND year < $this_year ORDER BY ranking DESC, year LIMIT 0,10";
		}
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Ranking</name>\n<name>Team and year</name>\n</headers>\n";
		while($row = mysqli_fetch_assoc($result)){
			echo "<row>\n<rank>".number_format($row['ranking'],2)."</rank>\n<team>$row[team]</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 6:
		if($this_year == $year_end) {
			$query = "SELECT ranking, team, year FROM DRL_points_and_ranks ORDER BY ranking DESC, year LIMIT 0,10";
		} else {
			$query = "SELECT ranking, team, year FROM DRL_points_and_ranks WHERE year < $this_year ORDER BY ranking DESC, year LIMIT 0,10";
		}
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Ranking</name>\n<name>Team and year</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)){
			echo "<row>\n<rank>".number_format($row['ranking'],2)."</rank>\n<team>$row[team]</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 8:
		if($this_year == $year_end) {
			$query = "SELECT tot_points, team, year FROM DRL_points_and_ranks ORDER BY tot_points DESC, year LIMIT 0,10";
		} else {
			$query = "SELECT tot_points, team, year FROM DRL_points_and_ranks WHERE year < $this_year ORDER BY tot_points DESC, year LIMIT 0,10";
		}
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Points</name>\n<name>Team and year</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<points>$row[tot_points]</points>\n<team>$row[team]</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 9:
		if($this_year == $year_end) {
			$query = "SELECT fault_diff, team, year FROM DRL_annual_fault_diff ORDER BY fault_diff DESC, year LIMIT 0,10";
		} else {
			$query = "SELECT fault_diff, team, year FROM DRL_annual_fault_diff WHERE year < $this_year ORDER BY fault_diff DESC, year LIMIT 0,10";
		}
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_end</year_limit>\n";
		echo "<headers>\n<name>Fault diff</name>\n<name>Team and year</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<fd>".number_format($row[fault_diff],2)."</fd>\n<team>$row[team]</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 10:
		$query = "SELECT no_of_teams, competition, year, location FROM DRL_event_overview ORDER BY no_of_teams DESC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Entry</name>\n<name>Competition</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<teams>$row[no_of_teams]</teams>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 11:
		$query = "SELECT tot_faults, competition, year, location, no_of_teams FROM DRL_event_overview ORDER BY tot_faults, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Competition</name>\n<name>Entry</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>$row[tot_faults]</faults>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n<teams>$row[no_of_teams]</teams>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 12:
		$query = "SELECT ave_faults, competition, year, location FROM DRL_event_overview ORDER BY ave_faults, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Ave.</name>\n<name>Competition</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row[ave_faults],2)."</faults>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";

		}
		echo "</reply>\n";
		break;
	case 13:
		$query = "SELECT tot_faults, competition, year, location, no_of_teams FROM DRL_event_overview ORDER BY tot_faults DESC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Competition</name>\n<name>Entry</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>$row[tot_faults]</faults>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n<teams>$row[no_of_teams]</teams>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 14:
		$query = "SELECT ave_faults, competition, year, location FROM DRL_event_overview ORDER BY ave_faults DESC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Ave.</name>\n<name>Competition</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row[ave_faults],2)."</faults>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";

		}
		echo "</reply>\n";
		break;
	case 15:
		$query = "SELECT * FROM DRL_event_victory_margins ORDER BY margin DESC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Margin</name>\n<name>Competition</name>\n<name>Winners and Runners Up</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<margin>".number_format($row[margin],2)."</margin>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp><year>$row[year]</year>";
			if($row['winners']=="Society of Royal Cumberland Youths") {
				echo "<teams>SRCY ($row[win_faults]) beat $row[runner_up] ($row[ru_faults])</teams>\n";
			} else if($row['runner_up']=="Society of Royal Cumberland Youths") {
				echo "<teams>$row[winners] ($row[win_faults]) beat SRCY ($row[ru_faults])</teams>\n";
			} else {
				echo "<teams>$row[winners] ($row[win_faults]) beat $row[runner_up] ($row[ru_faults])</teams>\n";
			}
			echo "</row>\n";
		}
		echo "</reply>\n";
		break;
	case 16:
		$query = "SELECT * FROM DRL_event_victory_margins ORDER BY margin, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Margin</name>\n<name>Competition</name>\n<name>Winners and Runners Up</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<margin>".number_format($row[margin],2)."</margin>\n";
			echo "<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp><year>$row[year]</year>";
			if($row['winners']=="Society of Royal Cumberland Youths") {
				echo "<teams>SRCY ($row[win_faults]) beat $row[runner_up] ($row[ru_faults])</teams>\n";
			} else if($row['runner_up']=="Society of Royal Cumberland Youths") {
				echo "<teams>$row[winners] ($row[win_faults]) beat SRCY ($row[ru_faults])</teams>\n";
			} else {
				echo "<teams>$row[winners] ($row[win_faults]) beat $row[runner_up] ($row[ru_faults])</teams>\n";
			}
			echo "</row>\n";
		}
		echo "</reply>\n";
		break;
	case 17:
		$query = "SELECT no_of_comps, team, year FROM DRL_teams_annual_overview ORDER BY no_of_comps DESC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Comps</name>\n<name>Team and year</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<comps>$row[no_of_comps]</comps>\n<team>".team_check($row[team])."</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 18:
		$query = "SELECT DRL_17_team_comps_per_season.team, SUM(DRL_17_team_comps_per_season.no_of_comps) AS comps, DRL_year_ranges.last_year, DRL_year_ranges.first_year FROM DRL_17_team_comps_per_season INNER JOIN DRL_year_ranges ON DRL_17_team_comps_per_season.team = DRL_year_ranges.team GROUP BY team ORDER BY comps DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Comps</name>\n<name>Team</name>\n<name>Years active</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<comps>$row[comps]</comps>\n<team>".team_check($row[team])."</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 19:
		$query = "SELECT DRL_results.faults, DRL_results.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_results INNER JOIN DRL_events ON DRL_results.event_id = DRL_events.event_id WHERE DRL_results.faults<>0 ORDER BY DRL_results.faults ASC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Team</name>\n<name>Competition</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>$row[faults]</faults>\n<team>".team_check($row[team])."</team>\n<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 20:
		$query = "SELECT DRL_results.faults, DRL_results.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_results INNER JOIN DRL_events ON DRL_results.event_id = DRL_events.event_id WHERE DRL_results.faults<>0 ORDER BY DRL_results.faults DESC, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Team</name>\n<name>Competition</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>$row[faults]</faults>\n<team>".team_check($row[team])."</team>\n<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 21:
		$query = "SELECT avg_faults, team, year, no_of_comps FROM DRL_teams_annual_overview WHERE no_of_comps >=5 ORDER BY avg_faults, no_of_comps, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Ave.</name>\n<name>Team and year</name>\n<name>Comps</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row['avg_faults'],2)."</faults>\n<team>".team_check($row[team])."</team>\n<year>$row[year]</year>\n<comps>$row[no_of_comps]</comps>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 22:
		$query = "SELECT avg_faults, team, year, no_of_comps FROM DRL_teams_annual_overview WHERE no_of_comps >=5 ORDER BY avg_faults DESC, no_of_comps, year LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Ave.</name>\n<name>Team and year</name>\n<name>Comps</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row['avg_faults'],2)."</faults>\n<team>".team_check($row[team])."</team>\n<year>$row[year]</year>\n<comps>$row[no_of_comps]</comps>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 23:
		$query = "SELECT tot_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY tot_faults LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>4</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Team</name>\n<name>Years active</name>\n<name>Comps</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row['tot_faults'],2)."</faults>\n<team>".team_check($row[team])."</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n<comps>$row[no_of_comps]</comps>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 24:
		$query = "SELECT tot_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY tot_faults DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>4</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Team</name>\n<name>Years active</name>\n<name>Comps</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row['tot_faults'],2)."</faults>\n<team>".team_check($row[team])."</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n<comps>$row[no_of_comps]</comps>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 25:
		$query = "SELECT avg_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY avg_faults LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>4</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Ave.</name>\n<name>Team</name>\n<name>Years active</name>\n<name>Comps</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row['avg_faults'],2)."</faults>\n<team>".team_check($row[team])."</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n<comps>$row[no_of_comps]</comps>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 26:
		$query = "SELECT avg_faults, team, first_year, last_year, no_of_comps FROM DRL_teams_all_time_over_view WHERE no_of_comps >=20 ORDER BY avg_faults DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>4</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Ave.</name>\n<name>Team</name>\n<name>Years active</name>\n<name>Comps</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>".number_format($row['avg_faults'],2)."</faults>\n<team>".team_check($row[team])."</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n<comps>$row[no_of_comps]</comps>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 27:
		$query = "SELECT DRL_event_winners.faults, DRL_event_winners.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_event_winners INNER JOIN DRL_events ON DRL_event_winners.event_id = DRL_events.event_id ORDER BY faults DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Team</name>\n<name>Competition</name>\n</headers>\n";	
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>$row[faults]</faults>\n<team>".team_check($row[team])."</team>\n<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 28:
		$query = "SELECT DRL_results.faults, DRL_results.team, DRL_events.competition, DRL_events.location, DRL_events.year FROM DRL_events INNER JOIN DRL_results ON DRL_events.event_id=DRL_results.event_id INNER JOIN DRL_event_overview ON DRL_events.event_id=DRL_event_overview.event_id WHERE DRL_results.faults <> 0 AND DRL_results.position = DRL_event_overview.no_of_teams ORDER BY DRL_results.faults LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Faults</name>\n<name>Team</name>\n<name>Competition</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<faults>$row[faults]</faults>\n<team>".team_check($row[team])."</team>\n<comp>".loc_check($row['competition'], $row['location'], $row['year'])."</comp>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 29:
		$query = "SELECT DRL_all_time_fault_diffs.fault_diff, DRL_all_time_fault_diffs.team, DRL_year_ranges.first_year, DRL_year_ranges.last_year FROM DRL_all_time_fault_diffs INNER JOIN DRL_year_ranges ON DRL_all_time_fault_diffs.team = DRL_year_ranges.team ORDER BY DRL_all_time_fault_diffs.fault_diff DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Fault diff</name>\n<name>Team</name>\n<name>Years active</name>\n</headers>\n";		
		while($row = mysqli_fetch_assoc($result)) {
			echo "<row>\n<fd>".number_format($row[fault_diff],2)."</fd>\n<team>$row[team]</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 31:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 32:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Devon 8 Bell' OR DRL_events.competition LIKE 'Major Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 33:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Devon 8 Bell' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 34:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE '%Qualifier' OR DRL_events.competition LIKE '%Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 35:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Major Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 36:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE 'Minor Final' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 37:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE DRL_events.competition LIKE '%Qualifier' GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 38:
		$query = "SELECT COUNT(DRL_events.competition) AS comps, ringers.forename, ringers.surname FROM ringers INNER JOIN (ringers_x_event inner join DRL_events ON ringers_x_event.event_id = DRL_events.event_id) ON ringers.ringer_id = ringers_x_event.ringer_id WHERE ringers_x_event.bell = 1 GROUP BY ringers.ringer_id ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 40:
		$query = "SELECT IF(tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps,tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps, tenor_ringers_6_bell.comps) AS comps, tenor_ringers_6_bell.forename, tenor_ringers_6_bell.surname FROM tenor_ringers_6_bell LEFT JOIN tenor_ringers_8_bell ON tenor_ringers_8_bell.surname=tenor_ringers_6_bell.surname UNION SELECT IF(tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps, tenor_ringers_6_bell.comps + tenor_ringers_8_bell.comps, tenor_ringers_8_bell.comps) as comps, tenor_ringers_8_bell.forename, tenor_ringers_8_bell.surname FROM tenor_ringers_8_bell LEFT JOIN tenor_ringers_6_bell ON tenor_ringers_8_bell.surname=tenor_ringers_6_bell.surname ORDER BY comps DESC, surname, forename LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_comp</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Ringer</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[comps]</wins>\n<ringer>$row[forename] $row[surname]</ringer>\n</row>\n";
		}
		echo "</reply>\n";
		break;		
	case 41:
		$query = "SELECT COUNT(DRL_results.position) AS wins, DRL_results.team, DRL_events.year FROM DRL_events INNER JOIN DRL_results ON DRL_events.event_id = DRL_results.event_id WHERE DRL_results.position = 1 GROUP BY team, year ORDER BY wins DESC, year ASC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Team and year</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[wins]</wins>\n<team>$row[team]</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 42:
		$query = "SELECT COUNT(DRL_results.position) AS wins, DRL_teams_all_time_over_view.team, DRL_teams_all_time_over_view.first_year, DRL_teams_all_time_over_view.last_year FROM DRL_teams_all_time_over_view INNER JOIN DRL_results ON DRL_teams_all_time_over_view.team=DRL_results.team WHERE DRL_results.position = 1 GROUP BY team ORDER BY wins DESC, last_year DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Wins</name>\n<name>Team</name>\n<name>Years active</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>$row[wins]</wins>\n<team>$row[team]</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 43:
		$query = "SELECT (DRL_event_wins.wins/DRL_teams_annual_overview.no_of_comps)*100 AS percentage, DRL_teams_annual_overview.team, DRL_teams_annual_overview.year FROM DRL_event_wins INNER JOIN DRL_teams_annual_overview ON DRL_event_wins.team = DRL_teams_annual_overview.team AND DRL_event_wins.year = DRL_teams_annual_overview.year WHERE DRL_teams_annual_overview.no_of_comps>5 GROUP BY team, year ORDER BY percentage DESC, year ASC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>2</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Percentage</name>\n<name>Team and year</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>".number_format($row['percentage'], 2)."</wins>\n<team>$row[team]</team>\n<year>$row[year]</year>\n</row>\n";
		}
		echo "</reply>\n";
		break;
	case 44:
		$query = "SELECT (SUM(DRL_event_wins.wins)/DRL_teams_all_time_over_view.no_of_comps)*100 AS percentage, DRL_teams_all_time_over_view.team, DRL_teams_all_time_over_view.first_year, DRL_teams_all_time_over_view.last_year FROM DRL_event_wins INNER JOIN DRL_teams_all_time_over_view ON  DRL_event_wins.team = DRL_teams_all_time_over_view.team WHERE DRL_teams_all_time_over_view.no_of_comps>20 GROUP BY team ORDER BY percentage DESC, last_year DESC LIMIT 0,10";
		$result = mysqli_query($cxn, $query);
		echo "<columns>3</columns>\n<year_limit>$year_start</year_limit>\n";
		echo "<headers>\n<name>Percentage</name>\n<name>Team</name>\n<name>Years active</name>\n</headers>\n";
		while($row=mysqli_fetch_assoc($result)) {
			echo "<row>\n<wins>".number_format($row['percentage'], 2)."</wins>\n<team>$row[team]</team>\n<first>$row[first_year]</first>\n<last>$row[last_year]</last>\n</row>\n";
		}
		echo "</reply>\n";
		break;

		
}
?>