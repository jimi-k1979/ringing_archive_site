<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
?>
<title>James Kerslake's Devon-style bellringing site - DRL stats</title>

<!-- banner and main menu for ladder pages -->
<?php
include("../allpages/league_banner.inc"); 

function name_to_acronym($name) {
	switch($name) {
		case "Newton Abbot, Ipplepen and Torbay Deaneries":
			$name = "NAIT Deaneries";
			break;
		case "Society of Royal Cumberland Youths":
			$name = "SRCY";
			break;
		case "Plympton and Ivybridge Deaneries":
			$name = "P&amp;I Deaneries";
			break;
	}
	
	return $name;
}

function location_check($event, $location, $year) {
	if(strcmp($event,$location)==0 ||
		$event == "Eggbuckland" ||
		$event == "Kilkhampton 8 Bell" ||
		$event == "Kilkhampton 6 Bell" ||
		$event == "Stratton 8 Bell" ||
		$event == "Stratton 6 Bell"||
			strcmp($event, "Dowland 5 Bell") == 0 ||
			strcmp($event, "Chittlehampton 6 Bell") == 0 ||
			(strcmp($event, "Chittlehampton 8 Bell") == 0 && $year!=1990)) {
		return $event;
	} else {
		$string = $event;
		$string .= " at ".$location;
		return $string;
	}
}

function stat_finaliser($records, $year, $stat_no, $rows) {
	$month = date("m"); // month number

	switch($stat_no) {
		case 1: // each years winners
			for($i=0; $i<$rows; $i++) {
				if($month!=12) {
					if($records[year][$i]==$year-1) {
						$stats[0][$i]="<span class='bold'>".$records[year][$i]."</span>";
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team_5][$i])."</span>";
						$stats[2][$i]="<span class='bold'>".name_to_acronym($records[team_ac][$i])."</span>";
						return $stats;
					} else {
						$stats[0][$i]=$records[year][$i];
						$stats[1][$i]=name_to_acronym($records[team_5][$i]);
						$stats[2][$i]=name_to_acronym($records[team_ac][$i]);
					}
				} else {
					if($records[year][$i]==$year) {
						$stats[0][$i]="<span class='bold'>".$records[year][$i]."</span>";
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team_5][$i])."</span>";
						$stats[2][$i]="<span class='bold'>".name_to_acronym($records[team_ac][$i])."</span>";
						return $stats;
					} else {
						$stats[0][$i]=$records[year][$i];
						$stats[1][$i]=name_to_acronym($records[team_5][$i]);
						$stats[2][$i]=name_to_acronym($records[team_ac][$i]);
					}
				}
			}
			break; // shouldn't get here...
		
		case 2: // number of competitions per year
			for($i=0; $i<$rows; $i++) {
				if($month!=12) {
					if($records[year][$i]==$year-1) {
						$stats[0][$i]="<span class='bold'>".$records[year][$i]."</span>";
						$stats[1][$i]="<span class='bold'>".$records[comps][$i]."</span>";
						return $stats;
					} else {
						$stats[0][$i]=$records[year][$i];
						$stats[1][$i]=$records[comps][$i];
					}
				} else {
					if($records[year][$i]==$year) {
						$stats[0][$i]="<span class='bold'>".$records[year][$i]."</span>";
						$stats[1][$i]="<span class='bold'>".$records[comps][$i]."</span>";
						return $stats;
					} else {
						$stats[0][$i]=$records[year][$i];
						$stats[1][$i]=$records[comps][$i];
					}
				}
			}
			break; // shouldn't get here...

		case 3: // number of teams per year
			for($i=0; $i<$rows; $i++) {
				if($month!=12) {
					if($records[year][$i]==$year-1) {
						$stats[0][$i]="<span class='bold'>".$records[year][$i]."</span>";
						$stats[1][$i]="<span class='bold'>".$records[teams_5][$i]."</span>";
						$stats[2][$i]="<span class='bold'>".$records[teams_ac][$i]."</span>";
						return $stats;
					} else {
						$stats[0][$i]=$records[year][$i];
						$stats[1][$i]=$records[teams_5][$i];
						$stats[2][$i]=$records[teams_ac][$i];
					}
				} else {
					if($records[year][$i]==$year) {
						$stats[0][$i]="<span class='bold'>".$records[year][$i]."</span>";
						$stats[1][$i]="<span class='bold'>".$records[teams_5][$i]."</span>";
						$stats[2][$i]="<span class='bold'>".$records[teams_ac][$i]."</span>";
						return $stats;
					} else {
						$stats[0][$i]=$records[year][$i];
						$stats[1][$i]=$records[teams_5][$i];
						$stats[2][$i]=$records[teams_ac][$i];
					}
				}
			}
			break; // shouldn't get here...

		case 4: // highest end of year ranking
		case 5: // these two have the same table style
			for($i=0; $i<$rows; $i++) {
				if($month!=12) {
					if($records[year][$i]==$year-1) {
						$stats[0][$i]=number_format($records[ranking][$i], 2, '.', ',');
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i]."</span>";
					} else if($records[year][$i]==$year) {
						$rows++;
					} else {
						$stats[0][$i]=number_format($records[ranking][$i], 2, '.', ',');
						$stats[1][$i]=name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i];
					}
				} else {
					if($records[year][$i]==$year) {
						$stats[0][$i]=number_format($records[ranking][$i], 2, '.', ',');
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i]."</span>";
					} else {
						$stats[0][$i]=number_format($records[ranking][$i], 2, '.', ',');
						$stats[1][$i]=name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i];
					}
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 8: // highest end of year points
			for($i=0; $i<$rows; $i++) {
				if($month!=12) {
					if($records[year][$i]==$year-1) {
						$stats[0][$i]=$records[tot_points][$i];
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i]."</span>";
					} else if($records[year][$i]==$year) {
						$rows++;
					} else {
						$stats[0][$i]=$records[tot_points][$i];
						$stats[1][$i]=name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i];
					}
				} else {
					if($records[year][$i]==$year) {
						$stats[0][$i]=$records[tot_points][$i];
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i]."</span>";
						} else {
						$stats[0][$i]=$records[tot_points][$i];
						$stats[1][$i]=name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i];
					}
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 9: // highest end of year fault differences
			for($i=0; $i<$rows; $i++) {
				if($month!=12) {
					if($records[year][$i]==$year-1) {
						$stats[0][$i]=number_format($records[fault_diff][$i], 2, '.', ',');
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i]."</span>";
					} else if($records[year][$i]==$year) {
						$rows++;
					} else {
						$stats[0][$i]=number_format($records[fault_diff][$i], 2, '.', ',');
						$stats[1][$i]=name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i];
					}
				} else {
					if($records[year][$i]==$year) {
						$stats[0][$i]=number_format($records[fault_diff][$i], 2, '.', ',');
						$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i]."</span>";
					} else {
						$stats[0][$i]=number_format($records[fault_diff][$i], 2, '.', ',');
						$stats[1][$i]=name_to_acronym($records[team][$i]);
						$stats[1][$i].=", ".$records[year][$i];
					}
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 10: // number of entrants per competition
			for($i=0; $i<$rows; $i++) {
				if($records[year][$i]==$year || ($month<=3 && $records[year][$i]==$year-1)) {
					$stats[0][$i]=$records[no_of_teams][$i];
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=$records[no_of_teams][$i];
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i];
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 11: // lowest / highest total faults
		case 13: // use the same table format
			for($i=0; $i<$rows; $i++) {
				if($records[year][$i]==$year || ($month<=3 && $records[year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[tot_faults][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i]." (".$records[no_of_teams][$i].")</span>";
				} else {
					$stats[0][$i]=number_format($records[tot_faults][$i], 2, '.', ',');
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i]." (".$records[no_of_teams][$i].")";
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 12: // lowest / highest average faults
		case 14: // use the same table format
			for($i=0; $i<$rows; $i++) {
				if($records[year][$i]==$year || ($month<=3 && $records[year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[ave_faults][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=number_format($records[ave_faults][$i], 2, '.', ',');
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i];
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 15: // lowest / highest average faults
		case 16: // use the same table format
			for($i=0; $i<$rows; $i++) {
				if($records[year][$i]==$year || ($month<=3 && $records[year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[margin][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=number_format($records[margin][$i], 2, '.', ',');
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
					$stats[1][$i].=", ".$records[year][$i];
				}
				$stats[2][$i]=name_to_acronym($records[winners][$i]);
				$stats[2][$i].=" (".$records[win_faults][$i].") beat ";
				$stats[2][$i].=name_to_acronym($records[runner_up][$i]);
				$stats[2][$i].=" (".$records[ru_faults][$i].")";
			}
			return $stats;
			break; // shouldn't get here...
			
		case 17: // no of comps per year
			for($i=0; $i<$rows; $i++) {
				if($records[year][$i]==$year || ($month<=3 && $records[year][$i]==$year-1)) {
					$stats[0][$i]=$records[no_of_comps][$i];
					$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=$records[no_of_comps][$i];
					$stats[1][$i]=name_to_acronym($records[team][$i]);
					$stats[1][$i].=", ".$records[year][$i];
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 18: // all time no of comps
			for($i=0; $i<$rows; $i++) {
				if($records[last_year][$i]==$year || ($month<=3 && $records[last_year][$i]==$year-1)) {
					$stats[0][$i]=$records[comps][$i];
					$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i])."</span>";
				} else {
					$stats[0][$i]=$records[comps][$i];
					$stats[1][$i]=name_to_acronym($records[team][$i]);
				}
			}
			return $stats;
			break; // shouldn't get here...

		case 19: // lowest / highest faults (generally and to win / lose)
		case 20:
		case 21:
		case 22: // all use the same format
			for($i=0; $i<$rows; $i++) {
				if($records[year][$i]==$year || ($month<=3 && $records[year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[faults][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i])."</span>";
				} else {
					$stats[0][$i]=number_format($records[faults][$i], 2, '.', ',');
					$stats[1][$i]=name_to_acronym($records[team][$i]);
				}
				$stats[2][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i],$records[year][$i]);
				$stats[2][$i].=", ".$records[year][$i];
			}
			return $stats;
			break; // shouldn't get here...

		case 23: // all time total faults
			for($i=0; $i<$rows; $i++) {
				if($records[last_year][$i]==$year || ($month<=3 && $records[last_year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[tot_faults][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i])."</span>";
					$stats[2][$i]=$records[first_year][$i];
					$stats[2][$i].=" -";
				} else {
					$stats[0][$i]=number_format($records[tot_faults][$i], 2, '.', ',');
					$stats[1][$i]=name_to_acronym($records[team][$i]);
					$stats[2][$i]=$records[first_year][$i];
					$stats[2][$i].=" - ".$records[last_year][$i];

				}
				$stats[3][$i]=$records[no_of_comps][$i];
			}
			return $stats;
			break; // shouldn't get here...

		case 24: // all time average faults
			for($i=0; $i<$rows; $i++) {
				if($records[last_year][$i]==$year || ($month<=3 && $records[last_year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[avg_faults][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i])."</span>";
					$stats[2][$i]=$records[first_year][$i];
					$stats[2][$i].=" -";
				} else {
					$stats[0][$i]=number_format($records[avg_faults][$i], 2, '.', ',');
					$stats[1][$i]=name_to_acronym($records[team][$i]);
					$stats[2][$i]=$records[first_year][$i];
					$stats[2][$i].=" - ".$records[last_year][$i];

				}
			}
			return $stats;
			break; // shouldn't get here...
	
		case 25: // all time average faults
			for($i=0; $i<$rows; $i++) {
				if($records[last_year][$i]==$year || ($month<=3 && $records[last_year][$i]==$year-1)) {
					$stats[0][$i]=number_format($records[fault_diff][$i], 2, '.', ',');
					$stats[1][$i]="<span class='bold'>".name_to_acronym($records[team][$i])."</span>";
					$stats[2][$i]=$records[first_year][$i];
					$stats[2][$i].=" -";
				} else {
					$stats[0][$i]=number_format($records[fault_diff][$i], 2, '.', ',');
					$stats[1][$i]=name_to_acronym($records[team][$i]);
					$stats[2][$i]=$records[first_year][$i];
					if($records[last_year][$i]!=$records[first_year][$i]) {
						$stats[2][$i].=" - ".$records[last_year][$i];
					}
				}
			}
			return $stats;
			break; // shouldn't get here...
		
		default: // shouldn't get here, but just in case!
			return $records;
	}
}
?>

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
<div id="sub_selected"><a class="sub_select" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>

<?php
	$year = $latest_year; // year four digits
	$month = date("m"); // month number

	
 	// single record or multiple records?
	$first_query = $_GET[firstat];
	if ($_GET[lastat]==NULL) { // only a single record or stat
		$last_query = $first_query;
		$one_rec_flag = 1;
		
		$page_head = "<div class='head_2'>DRL statistics and records</div>\n";
	} else { // a group of records
		$last_query = $_GET[lastat];
		$one_rec_flag = 0;

		switch ($last_query) {
			case 9: // league records
				$page_head = "<div class='head_2'>DRL League records</div>
<div class='text'>The records on this page are the league records, mainly using single season results. Many of the records are spilt into two categories: Teams to have rung in five or more competitions in a season and those for all participating teams (the all-comers records). All time records are since $earliest_year, and records set in ";
				if($month!=12) {
					$page_head .= $year-1;
				} else {
					$page_head .= $year;
				}
				$page_head .=	" are marked with <span class='bold'>bold</span> text.</div>\n";
				break;
			case 16: // competition records
				$page_head = "<div class='head_2'>DRL Competition records</div>
<div class='text'>The records in this section are for single events held since $earliest_year. Only competitions that were used in the calculation of the league tables are considered in the records. The abbreviations NAIT, P&amp;I and SRCY are used for Newton Abbot, Ipplepen and Torbay [Deaneries], Plympton and Ivybridge [Deaneries] and The Society of Royal Cumberland Youths respectively throughout. <span class='bold'>Bold</span> type indicates records set in ";
				if($month<=4) {
					$page_head .= $year-1 .".</div>\n";
				} else {
					$page_head .= $year.".</div>\n";
				}
				break;
			case 26: // team records
				$page_head = "<div class='head_2'>DRL Team records</div>
<div class='text'>The records in this section are for individual teams. The records are split into two sections: first those for one season and then the all time records starting from the $earliest_year. The acornyms NAIT, P&amp;I and SRCY are used for Newton Abbot, Ipplepen and Torbay [Deaneries], Plympton and Ivybridge [Deaneries] and The Society of Royal Cumberland Youths respectively throughout. <span class='bold'>Bold</span> type indicates records set in ";
				if($month<=4) {
					$page_head .= $year-1 ." or teams active in ";
					$page_head .= $year-1 .".</div>\n";
				} else {
					$page_head .= $year." or teams active in ";
					$page_head .= $year.".</div>\n";
				}				
				break;
		}
	}	
	
	// connect to database
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
	echo $page_head."\n	<div class='text' style='text-align:center'><a href='drl_stats_home.php'>Back to stats home page</a>\n";
	
	for($i=$first_query; $i<=$last_query; $i++) {
		// get the details of the table
		$query_1 = "SELECT * FROM stat_tables WHERE stat_id = $i";
		$result_1 = mysqli_query($cxn, $query_1);
		$stat_details = mysqli_fetch_assoc($result_1);
		
		echo "	<div class='head_3'>$stat_details[name]</div>\n";
		if($i!=1 && $i!=2 && $i!=3 && $i!=6 && $i!=7 && $i!=26) {
			if($one_rec_flag==1){
				echo "			<div class='small' style='text-align:center'>Top 100$stat_details[constraint]</div>\n";
			} else {
				echo "			<div class='small' style='text-align:center'>Top 25$stat_details[constraint]</div>\n";
			}
		} else if($i==26) {
			echo "			<div class='small' style='text-align:center'>$stat_details[constraint]</div>\n";
		}
		
		// process the stat tables		
		if($i == 6) { // biggest and smallest league victory margins			
		
			if($month==12) {
				$year_target=$year+1;
			} else {
				$year_target=$year;
			}
			
			// intialise big margin arrays
			$big_margin_5[margin] = 0;
			$big_margin_5[year] = 0;
			$big_margin_5[team_1] = " ";
			$big_margin_5[team_2] = " ";
			$big_margin_ac[margin] = 0;
			$big_margin_ac[year] = 0;
			$big_margin_ac[team_1] = " ";
			$big_margin_ac[team_2] = " ";

			//initialise the zero margin flags
			$zero_margin_5=0;
			$zero_margin_ac=0;
			
			// 5+ comps first
			// scan through each year, not including the current one
			for($year_loop=$earliest_year;$year_loop<$year_target;$year_loop++) {
				// get the top two from the league table
				$query_2 = "SELECT team, ranking, fault_diff FROM DRL_all_time_table WHERE year=$year_loop AND no_of_comps>=5 LIMIT 0,2";
				$result_2 = mysqli_query($cxn, $query_2);
				$loop=0;
				while($row = mysqli_fetch_assoc($result_2)) {
					foreach($row as $key => $value) {
						$column[$key][$loop] = $value;
					}
					$loop++;
				}
				
				// calculate the margin
				$margin = $column[ranking][0]-$column[ranking][1];
				
				if($year_loop==$earliest_year) { // initialise small margin array with $earliest_year's margin
					$little_margin_5[margin] = $margin;
					$little_margin_5[year] = $earliest_year;
					$little_margin_5[team_1] = $column[team][0];
					$little_margin_5[team_1] .= " (".number_format($column[ranking][0],2); // add ranking to team
					$little_margin_5[team_2] = $column[team][1];
					$little_margin_5[team_2] .= " (".number_format($column[ranking][1],2); // add ranking to team
				}

				// is the margin the smallest or biggest?
				if($margin<$little_margin_5[margin]) { // check against the smallest first
					$little_margin_5[margin] = $column[ranking][0]-$column[ranking][1];
					$little_margin_5[year] = $year_loop;
					$little_margin_5[team_1] = $column[team][0];
					$little_margin_5[team_1] .= " (".number_format($column[ranking][0],2); // add ranking to team
					$little_margin_5[team_2] = $column[team][1];
					$little_margin_5[team_2] .= " (".number_format($column[ranking][1],2); // add ranking to team
					
					if($margin<0.01) { // if the margin is 0 get the fault differences
						$zero_margin_5=1;
						$fault_difference_5[0]=$column[fault_diff][0];
						$fault_difference_5[1]=$column[fault_diff][1];
					}
				} else if ($margin>$big_margin_5[margin]) { // if not the smallest, is it the biggest?
					$big_margin_5[margin] = $column[ranking][0]-$column[ranking][1];
					$big_margin_5[year] = $year_loop;
					$big_margin_5[team_1] = $column[team][0];
					$big_margin_5[team_1] .= " (".number_format($column[ranking][0],2); // add ranking to team
					$big_margin_5[team_2] = $column[team][1];
					$big_margin_5[team_2] .= " (".number_format($column[ranking][1],2); // add ranking to team
				}
			} // end for loop scanning the years
			
			// all-comers now
			// scan through each year, not including the current one
			for($year_loop=$earliest_year;$year_loop<$year_target;$year_loop++) {
				// get the top two from the league table
				$query_2 = "SELECT team, ranking, fault_diff FROM DRL_all_time_table WHERE year=$year_loop LIMIT 0,2";
				$result_2 = mysqli_query($cxn, $query_2);
				$loop=0;
				while($row = mysqli_fetch_assoc($result_2)) {
					foreach($row as $key => $value) {
						$column[$key][$loop] = $value;
					}
					$loop++;
				}
				
				// calculate the margin
				$margin = $column[ranking][0]-$column[ranking][1];
				
				if($year_loop==$earliest_year) { // initialise small margin array with 2003's margin
					$little_margin_ac[margin] = $margin;
					$little_margin_ac[year] = $earliest_year;
					$little_margin_ac[team_1] = $column[team][0];
					$little_margin_ac[team_1] .= " (".number_format($column[ranking][0],2); // add ranking to team
					$little_margin_ac[team_2] = $column[team][1];
					$little_margin_ac[team_2] .= " (".number_format($column[ranking][1],2); // add ranking to team
				}

				// is the margin the smallest or biggest?
				if($margin<$little_margin_ac[margin]) { // check against the smallest first
					$little_margin_ac[margin] = $column[ranking][0]-$column[ranking][1];
					$little_margin_ac[year] = $year_loop;
					$little_margin_ac[team_1] = $column[team][0];
					$little_margin_ac[team_1] .= " (".number_format($column[ranking][0],2); // add ranking to team
					$little_margin_ac[team_2] = $column[team][1];
					$little_margin_ac[team_2] .= " (".number_format($column[ranking][1],2); // add ranking to team
					
					if($margin==0) { // if the margin is 0 get the fault differences
						$zero_margin_ac=1;
						$fault_difference_ac[0]=$column[fault_diff][0];
						$fault_difference_ac[1]=$column[fault_diff][1];
					}
				} else if ($margin>$big_margin_ac[margin]) { // if not the smallest, is it the biggest?
					$big_margin_ac[margin] = $column[ranking][0]-$column[ranking][1];
					$big_margin_ac[year] = $year_loop;
					$big_margin_ac[team_1] = $column[team][0];
					$big_margin_ac[team_1] .= " (".number_format($column[ranking][0],2); // add ranking to team
					$big_margin_ac[team_2] = $column[team][1];
					$big_margin_ac[team_2] .= " (".number_format($column[ranking][1],2); // add ranking to team
				}
			} // end for loop scanning the years
	
			// display the results
			echo "<div class='head_4'>Largest victory margin - 5 or more competitions:</div>\n";
			echo "<div class='text' style='text-align:center'><span class='bold'>";
			echo number_format($big_margin_5[margin],2)."</span> in $big_margin_5[year] ";
			echo "<span class='italic'>$big_margin_5[team_1]) beat $big_margin_5[team_2])</span></div>\n";
			
			echo "<div class='head_4'>Largest victory margin - All-comers:</div>\n";
			echo "<div class='text' style='text-align:center'><span class='bold'>";
			echo number_format($big_margin_ac[margin],2)."</span> in $big_margin_ac[year] ";
			echo "<span class='italic'>$big_margin_ac[team_1]) beat $big_margin_ac[team_2])</span></div>\n";

			echo "<div class='head_4'>&nbsp;</div>\n";
			
			echo "<div class='head_4'>Smallest victory margin - 5 or more competitions:</div>\n";
			echo "<div class='text' style='text-align:center'><span class='bold'>";
			echo number_format($little_margin_5[margin],2)."</span> in $little_margin_5[year] ";
			if($zero_margin_5==1) {
				echo "<span class='italic'>$little_margin_5[team_1], fault difference: $fault_difference_5[0]) beat ";
				echo "$little_margin_5[team_2], fault difference: $fault_difference_5[1])</span></div>\n";
			} else {
				echo "<span class='italic'>$little_margin_5[team_1]) beat $little_margin_5[team_2])</span></div>\n";
			}

			echo "<div class='head_4'>Smallest victory margin - All-comers:</div>\n";
			echo "<div class='text' style='text-align:center'><span class='bold'>";
			echo number_format($little_margin_ac[margin],2)."</span> in $little_margin_ac[year] ";
			if($zero_margin_ac==1) {
				echo "<span class='italic'>$little_margin_ac[team_1], fault difference: $fault_difference_ac[0]) beat ";
				echo "$little_margin_ac[team_2], fault difference: $fault_difference_ac[1])</span></div>\n";
			} else {
				echo "<span class='italic'>$little_margin_ac[team_1]) beat $little_margin_ac[team_2])</span></div>\n";
			}

		} else if($i == 7) { // Most league wins
			$query_2 = $stat_details[query];
			$result_2 = mysqli_query($cxn, $query_2);
			$loop = 0;
			while($row = mysqli_fetch_assoc($result_2)) {// returns each year's winner
				foreach($row as $key => $value) {
					$column[$key][$loop] = $value;
				}
				$loop++;
			} 

			// 5+ comps	
			$query_3 = "SELECT team, COUNT(year) AS wins FROM DRL_01a_winning_team_over_5 GROUP BY team ORDER BY wins DESC";
			$result_3 = mysqli_query($cxn, $query_3);
			$most_wins = mysqli_fetch_assoc($result_3);
			
			if($column[team_5][$loop-1]==$most_wins[team] && $month!=12) { // ignore this year's result
				$most_wins[wins]--;
			}
			// decide whether to make the text bold or not
			if(($month!=12 && $column[team_5][$loop-2]==$most_wins[team]) || 
				($month==12 && $column[team_5][$loop-1]==$most_wins[team])) {
				$bold_flag=1;
			}
								
			// display the result
			echo "<div class='head_4'>5 or more competitions:</div>\n";
			echo "<div class='text' style='text-align:center'><span class='bold'>$most_wins[wins], ";
			if($bold_flag==1)	{
				echo "<span class='italic'>".$most_wins[team]."</span>, ";
				$counter=1;
				foreach($column[team_5] as $loop_2 => $value) {
					if(strcmp($value, $most_wins[team])==0) {
						if($counter!=$most_wins[wins]) {
							echo $column[year][$loop_2].", ";
							$counter++;
						} else {
							echo $column[year][$loop_2]."</span></div>\n";
							break;
						}
					}
				}
			} else {
				echo "</span><span class='italic'>$most_wins[team]</span>, ";
				$counter=1;
				foreach($column[team_5] as $loop_2 => $value) {
					if(strcmp($value, $most_wins[team])==0) {
						if($counter!=$most_wins[wins]) {
							echo $column[year][$loop_2].", ";
							$counter++;
						} else {
							echo $column[year][$loop_2]."</div>\n";
							break;
						}
					}
				}
			}

			// all comers... 
			$query_3 = "SELECT team, COUNT(year) AS wins FROM DRL_01b_winning_team_all_comers GROUP BY team ORDER BY wins DESC";
			$result_3 = mysqli_query($cxn, $query_3);
			$most_wins = mysqli_fetch_assoc($result_3);
			
			if($column[team_ac][$loop-1]==$most_wins[team] && $month!=12) { // ignore this year's result
				$most_wins[wins]--;
			}
			// decide whether to make the text bold or not
			if(($month!=12 && $column[team_ac][$loop-2]==$most_wins[team]) ||
				($month==12 && $column[team_ac][$loop-1]==$most_wins[team])) {
				$bold_flag=1;
			}
								
			// display the result
			echo "<div class='head_4'>All comers:</div>\n";
			echo "<div class='text' style='text-align:center'><span class='bold'>$most_wins[wins], ";
			if($bold_flag==1)	{
				echo "<span class='italic'>".$most_wins[team]."</span>, ";
				$counter=1;
				foreach($column[team_ac] as $loop_2 => $value) {
					if(strcmp($value, $most_wins[team])==0) {
						if($counter!=$most_wins[wins]) {
							echo $column[year][$loop_2].", ";
							$counter++;
						} else {
							echo $column[year][$loop_2]."</span>";
							break;
						}
					}
				}
			} else {
				echo "</span><span class='italic'>$most_wins[team]</span>, ";
				$counter=1;
				foreach($column[team_ac] as $loop_2 => $value) {
					if(strcmp($value, $most_wins[team])==0) {
						if($counter!=$most_wins[wins]) {
							echo $column[year][$loop_2].", ";
							$counter++;
						} else {
							echo $column[year][$loop_2];
							break;
						}
					}
				}
			}

		} else if($i == 26) { // DQs and NRs
			// do stuff
			$query_2 = $stat_details[query];
			$result_2 = mysqli_query($cxn, $query_2);
			$loop = 0;
			while($row = mysqli_fetch_assoc($result_2)) {// gets the number of disqualifications for each year
				foreach($row as $key => $value) {
					$column[$key][$loop] = $value;
				}
				$loop++;
			}
			
			$query_3 = "SELECT * FROM DRL_no_results";
			$result_3 = mysqli_query($cxn, $query_3);
			$count = 0;
			while($row = mysqli_fetch_assoc($result_3)) { // get the disqualified teams
				foreach($row as $key => $value) {
					$teams[$key][$count] = $value;
				}
				$count++;
			}
			
			$j=0;
			$end_row=0;
			foreach($column[year] as $key => $value) {
				$end_row += $column[DQs][$key];
				echo "<div class='head_4'>$value</div>\n";
				echo "<div class='text' style='text-align:center'><span class='bold'>".$column[DQs][$key]."</span></div>\n";
				echo "<div class='small' style='text-align:justify;width:400px;margin: 0 auto 10px auto'>\n\t";
				while($j<$end_row) {
					echo "<span class='bold'>".$teams[team][$j]."</span> <span class='italic'>at ";
					if(strcmp($teams[competition][$j],$teams[location][$j])==0 ||
						$teams[competition][$j] == "Eggbuckland" ||
						$teams[competition][$j] == "Kilkhampton 8 Bell" ||
						$teams[competiiton][$j] == "Kilkhampton 6 Bell" ||
						$teams[competition][$j] == "Stratton 8 Bell" ||
						$teams[competition][$j] == "Stratton 6 Bell"||
			strcmp($teams[competition][$j], "Dowland 5 Bell") == 0 ||
			strcmp($teams[competition][$j], "Chittlehampton 6 Bell") == 0 ||
			(strcmp($teams[competition][$j], "Chittlehampton 8 Bell") == 0 && $column[year]!=1990)) {
							echo $teams[competition][$j];
					} else {
						echo $teams[competition][$j]." (at ".$teams[location][$j].")";
					}
					$j++;
					if($j==$end_row) {
						echo "</span>\n</div>";
					} else {
						echo "</span>,\n\t";
					}
				}
			}
			
		} else { // all other records
			// get the column details
			$query_2 = "SELECT col_title, width, style FROM stat_columns WHERE stat_id = $i";
			$result_2 = mysqli_query($cxn, $query_2);
			$loop = 0;
			while($row = mysqli_fetch_assoc($result_2)) {
				foreach($row as $key => $value)	{
					$column[$key][$loop] = $value;
				}
				$loop++;
			}
			$col_no = $loop; // total number of columns
		
			// run the statistic query
			$query_3 = $stat_details[query];
			
			// on single query give top 100 on the main page give top 25s
			if($i>=8 || $i<=25 || $i==4 || $i==5) {
				if($one_rec_flag==1) { // single query
					$query_3 .= " LIMIT 0,130";
					$height = 1900;
				} else {
					$query_3 .= " LIMIT 0,40";
					$height = 475;
				}
			}

			$result_3 = mysqli_query($cxn, $query_3);
			$loop = 0;
			while($row = mysqli_fetch_assoc($result_3)) {
				foreach($row as $key => $value) {
					$records[$key][$loop] = $value;
				}
				$loop++;
			}
			
			if($i<=3){
				$height=19*$loop;
			}			
			
			// sort out the actual columns
			if($one_rec_flag==1) { // single query
				$stats = stat_finaliser($records, $year, $i, 100);
			} else {
				$stats = stat_finaliser($records, $year, $i, 25);
			}
					
			// display the column headers
			echo "<table>\n<tr>\n";
			for($j=0; $j<$col_no; $j++) {
				echo "		<th>".$column[col_title][$j]."</th>\n";
			}
			echo "	</tr>\n<tr>\n";
	
			// write contents		
			$j=0;
			foreach($stats as $key => $arr) { // loop through the columns
				echo "<td";
				if($column[style][$j]!=NULL) {
					echo " style='".$column[style][$j]."'";
				}
				echo ">";
				foreach($arr as $value) {
					echo "$value<br />\n";
				}
				echo "</td>\n";
				$j++;
			}
		}
		echo "</tr>\n</table>\n\n";
		if($stat_details[footer]!=NULL && $one_rec_flag==1) {
			echo "		<div class='small' style='text-align:center'>$stat_details[footer]</div>\n";
		}

	}	
?>


<div class='text' style='text-align:center'><a href="drl_stat_home.php">Back to stats home page</a></div>

</div>
</body>
</html>
