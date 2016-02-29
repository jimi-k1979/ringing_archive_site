<?php
function loc_check($comp, $loc, $year) {
	switch($comp) {
		case $loc:
		case "Alphington":
		case "Chittlehampton 6 Bell":
		case "Dowland 5 Bell":
		case "Eggbuckland":
		case "Kilkhampton 8 Bell":
		case "Kilkhampton 6 Bell":
		case "Stratton 8 Bell":
		case "Stratton 6 Bell":
		case "Bickleigh":
		case "Kilkhampton - John Cornish Shield":
			return $comp;
			break; // shouldn't get here!
		case "Chittlehampton 8 Bell":
			if($year==1990) {
				return $comp." <span class=\"italic\">(held at ".$loc.")</span>";
			} else {
				return $comp;
			}
			break; // shouldn't get here!
		case "Newton Abbot, Ipplepen and Torbay Deaneries":
			return "NAIT Deaneries <span class=\"italic\">(held at ".$loc.")</span>";
			break;
		case "Plymouth and Ivybridge Deaneries":
			return "P&amp;I Deaneries <span class=\"italic\">(held at ".$loc.")</span>";
			break;
		case "Littleham":
			if($year==2010) {
				return $comp." <span class=\"italic\">(held at ".$loc.")</span>";
			} else {
				return $comp;
			}
			break;
		default:
			return $comp." <span class=\"italic\">(held at ".$loc.")</span>";
			break; // shouldn't get here!
	}
}

function team_check($team) {
	if($team == "Society of Royal Cumberland Youths") {
		return "SRCY";
	} else {
		return $team;
	}
}

function league_winners($first_year, $last_year) {
	include("../allpages/dbinfo.inc");
    $cxn = mysqli_connect($host, $user, $passwd, $database);
	$query = "SELECT DRL_winners_over_5.year, DRL_winners_over_5.team AS five, DRL_winners_all_comers.team AS ac FROM DRL_winners_over_5 INNER JOIN DRL_winners_all_comers ON DRL_winners_over_5.year = DRL_winners_all_comers.year WHERE DRL_winners_over_5.year<=$last_year AND DRL_winners_over_5.year>=$first_year ORDER BY year DESC";
	$result = mysqli_query($cxn, $query);
	$row = mysqli_fetch_assoc($result);
	$i=0;
	$last = array ('year' => 1900, 'five' => "", 'ac' => "");
	while($row['year']>$year_end-10) {
		if($row['year']!=$last['year']) {
			$league_winners[$i][0]=$row['year'];
			if($row['year']==$last_year) {
				$league_winners[$i][1]="<span class=\"bold\">".team_check($row['five'])."</span>";
				$league_winners[$i][2]="<span class=\"bold\">".team_check($row['ac'])."</span>";
			} else {
				$league_winners[$i][1]=team_check($row['five']);
				$league_winners[$i][2]=team_check($row['ac']);
			}
			$last = $row;
			$i++;
		}
		$row = mysqli_fetch_assoc($result);
	}
    mysqli_close($cxn);
    return $league_winners;
}

function no_results($year_start, $first_year) {
	include("../allpages/dbinfo.inc");
    $cxn = mysqli_connect($host, $user, $passwd, $database);
	// DQs and NRs
	// fetch the number of dqs per year
	$return_string = "<table>\n";
	for($year = $year_start; $year>=$first_year; $year--) { // do years in reverse order
		$query = "SELECT year, COUNT(team) AS DQs FROM DRL_no_results WHERE year = $year GROUP BY year ORDER BY year";
		$result = mysqli_query($cxn, $query);
		$dq_rows = mysqli_num_rows($result);
		if($dq_rows==0) {
			// ignore any year with none, apart from this year
			if($year==$year_start) $return_string .= "<tr><th>$year: 0</th></tr>\n<tr><td>&nbsp;</td></tr>\n";
		} else {
			$num_dqs = mysqli_fetch_assoc($result); // gets the number of disqualifications for each year
			$return_string .= "<tr><th>$year: $num_dqs[DQs]</th></tr>\n";
			// get all the details
			$query = "SELECT * FROM DRL_no_results WHERE year = $year";
			$result = mysqli_query($cxn, $query);
			$dq_team_rows = mysqli_num_rows($result);
			for($i=0; $i<$dq_team_rows-1; $i++) {
				$row = mysqli_fetch_assoc($result); // get the disqualified teams
				$return_string .= "<tr class=\"small\"><td class=\"center\"><span style=\"font-weight:bold\">".team_check($row['team'])."</span>: ";
				$return_string .= "<span class=\"italic\">".loc_check($row['competition'], $row['location'], $row['year'])."</span>,</td></tr>";
			}
			$row = mysqli_fetch_assoc($result);
			$return_string .= "<tr class=\"small\"><td class=\"center\"><span style=\"font-weight:bold\">".team_check($row['team'])."</span>: ";
			$return_string .= "<span class=\"italic\">".loc_check($row['competition'], $row['location'], $row['year'])."</span></td></tr>\n";
		}
	}
	$retun_string .= "</table>\n";
//	echo "<p>$return_string;</p>";
	return $return_string;
	mysqli_close($cxn);
}
?>