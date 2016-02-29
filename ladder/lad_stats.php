<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
?>
<title>James Kerslake's Devon-style bellringing site - DRL stats</title>

<!-- banner and main menu for ladder pages -->
<?php
include("../allpages/league_banner.inc"); 

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
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=$records[no_of_teams][$i];
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
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
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
					$stats[1][$i].=", ".$records[year][$i]." (".$records[no_of_teams][$i].")</span>";
				} else {
					$stats[0][$i]=number_format($records[tot_faults][$i], 2, '.', ',');
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
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
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=number_format($records[ave_faults][$i], 2, '.', ',');
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
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
					$stats[1][$i]="<span class='bold'>".location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
					$stats[1][$i].=", ".$records[year][$i]."</span>";
				} else {
					$stats[0][$i]=number_format($records[margin][$i], 2, '.', ',');
					$stats[1][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
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
				$stats[2][$i]=location_check(name_to_acronym($records[competition][$i]),$records[location][$i]);
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
<div id="sub_menu_container" style="width:545px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if(date("m")<=3) {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".(date("Y")-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".date("Y")."'>Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_selected"><a class="sub_select" href="drl_stat_home.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>

<?php
	$year = date("Y"); // year four digits
	
 	// single record or multiple records?
	$first_query = $_GET[firstat];
	if ($_GET[lastat]==NULL) { // only a single record or stat
		$last_query = $first_query;
		$one_rec_flag = 1;
		
		$page_head = "<div class='head_2'>Ringing Ladder statistics and records</div>\n";
	} else { // a group of records
		$last_query = $_GET[lastat];
		$one_rec_flag = 0;

		switch ($last_query) {
			case 9: // league records
				$page_head = "<div class='head_2'>DRL League records</div>
<div class='text'>The records on this page are the league records, mainly using single season results. Many of the records are spilt into two categories: Teams to have rung in five or more competitions in a season and those for all participating teams (the all-comers records). All time records are since 2003, and records set in ";
				if($month!=12) {
					$page_head .= $year-1;
				} else {
					$page_head .= $year;
				}
				$page_head .=	" are marked with <span class='bold'>bold</span> text.</div>\n";
				break;
			case 16: // competition records
				$page_head = "<div class='head_2'>DRL Competition records</div>
<div class='text'>The records in this section are for single events held since 2003. Only competitions that were used in the calculation of the league tables are considered in the records. The abbreviations NAIT, P&amp;I and SRCY are used for Newton Abbot, Ipplepen and Torbay [Deaneries], Plympton and Ivybridge [Deaneries] and The Society of Royal Cumberland Youths respectively throughout. <span class='bold'>Bold</span> type indicates records set in ";
				if($month<=4) {
					$page_head .= $year-1 .".</div>\n";
				} else {
					$page_head .= $year.".</div>\n";
				}
				break;
			case 26: // team records
				$page_head = "<div class='head_2'>DRL Team records</div>
<div class='text'>The records in this section are for individual teams. The records are split into two sections: first those for one season and then the all time records starting from the DRL's first season in 2003. The acornyms NAIT, P&amp;I and SRCY are used for Newton Abbot, Ipplepen and Torbay [Deaneries], Plympton and Ivybridge [Deaneries] and The Society of Royal Cumberland Youths respectively throughout. <span class='bold'>Bold</span> type indicates records set in ";
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
	
	for($query_number=$first_query; $query_number<=$last_query; $query_number++) {
		// get the details of the table
		$query_1 = "SELECT * FROM stat_tables WHERE stat_id = $i";
		$result_1 = mysqli_query($cxn, $query_1);
		$stat_details = mysqli_fetch_assoc($result_1);
		
		echo "	<div class='head_3'>$stat_details[name]</div>\n";

		switch($query_number){ // biggest and smallest league victory margins		
			case 27: // A-section champions
			case 28: // B-section champtions
			case 29: // Rounds section champions
				break;
			case 30: // a-section promoted/relegated
			case 31: // b-section promoted/relegated
			case 32: // rounds section promoted/relefated
	}	
?>


<div class='text' style='text-align:center'><a href="drl_stat_home.php">Back to stats home page</a></div>

</div>
</body>
</html>
