<?php
include ("../allpages/dbinfo.inc");
include ("../league/drl_stat_queries.php");
$cxn = mysqli_connect($host, $user, $passwd, $database);

function display_comp($cxn, $event, $drl_flag) {
	if($drl_flag) { // true means drl, false means other comps
		$prefix = "DRL_";
	} else {
		$prefix = "other_"; 
	}
	
	$query = "SELECT position, team, faults FROM ".$prefix."results WHERE event_id=$event ORDER BY position ASC";
	$result = mysqli_query($cxn, $query);
	echo "<table>\n<tr>\n<th>&nbsp;</th>\n<th>Team</th>\n<th>Faults</th>\n</tr>\n";
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr>\n<td class=\"right\">$row[position]</td>\n<td>$row[team]</td>\n<td class=\"right\">$row[faults]</td>\n</tr>";
	}	
	echo "</table>";
}

function pick_comp($cxn, $comp_name, $year) {
	// sort out which competitions are needed
	echo "<p>$comp_name<br>$year</p>";
	switch ($comp_name) {
		case "Beric Bartlett":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// intermediate and novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					if(substr_compare($event['competition'], "intermediate", 17)) {
						echo "<h4>Intermediate Section</h4>\n";
					} else {
						echo "<h4>Novice Section</h4>\n";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;
			
		case "Burrington 3 Tower":
			// overall
			$query = "SELECT event_id FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year Burrington 3 Tower</h3>\n";
			echo $content."<h4>Overall (handicap)</h4>";
			display_comp($cxn, $event['event_id'], false);

			// Ashreigney
			$query = "SELECT event_id FROM DRL_events WHERE competition='Ashreigney' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h4>Ashreigney</h4>\n";
			display_comp($cxn, $event['event_id'], true);
		
			// Atherington
			$query = "SELECT event_id FROM DRL_events WHERE competition='Atherington' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h4>Atherington</h4>\n";
			display_comp($cxn, $event['event_id'], true);

			// Burrington
			$query = "SELECT event_id FROM DRL_events WHERE competition='Burrington' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h4>Burrington</h4>\n";
			display_comp($cxn, $event['event_id'], true);
			break;
			
		case "Cadbury Deanery":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// intermediate section
			$query = "SELECT event_id FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Intermediate Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			}
			break;
			
		case "Cadbury Deanery Winter League":
			// overall
			$query = "SELECT event_id, location, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition";
			echo "<h3>$year Cadbury Deanery Winter League</h3>\n";
			$result = mysqli_query($cxn, $query);
			while($event = mysqli_fetch_assoc($result)) {
				switch(substr($event['competition'], -1)) {
					case "l": // overall results
						echo "<h4>Overall results</h4>";
						break;
					case "1":
						echo "<h4>Round 1 - held at $event[location]</h4>";
						break;
					case "2":
						echo "<h4>Round 2 - held at $event[location]</h4>";
						break;
					case "3":
						echo "<h4>Round 3 - held at $event[location]</h4>";
						break;
					case "4":
						echo "<h4>Round 4 - held at $event[location]</h4>";
						break;
					case "5":
						echo "<h4>Round 5 - held at $event[location]</h4>";
						break;
					case "6":
						echo "<h4>Round 6 - held at $event[location]</h4>";
						break;
				}
				display_comp($cxn, $event['event_id'], false);
			}
			break;
			
		case "Chittlehampton (6 and 8 bell)":
			$query = "SELECT event_id, competition FROM DRL_events WHERE competition LIKE 'Chittlehampton%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			echo "<h3>$year Chittlehampton Competitions";
			if($year == 1990) {
				echo " <span class=\"italic\">(held at South Molton)</span>";
			}
			echo "</h3>\n";
			while($event = mysqli_fetch_assoc($result)) {
				echo "<h4>".substr($event['competition'], 15)."</h4>";
				display_comp($cxn, $event['event_id'], true);
			}
			break;

		case "Dartmouth, St Petrox":
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' and year=$year";
			$result = mysqli_query($cxn, $query);
			echo "<h3>Dartmouth St Petrox Novice Event</h3>\n";
			while($event = mysqli_fetch_assoc($result)) {
				echo "<h4>".substr($event['competition'], 23)."</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			}
			break;

		case "Devon Association Novice":
			$query = "SELECT event_id, competition, location FROM other_events WHERE competition LIKE '$comp_name%' and year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h3>$year Devon Association Novice Competition<br><span class=\"italic\">held at $event[location]</span></h3>\n";
			echo "<h4>Call Changes Section</h4>\n";
			display_comp($cxn, $event['event_id'], false);
			$event = mysqli_fetch_assoc($result);
			echo "<h4>Rounds Section</h4>\n";
			display_comp($cxn, $event['event_id'], false);
			break;
			
		case "Hartland Deanery":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;

		case "Kenn Deanery":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// intermediate and novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					if(substr_compare($event['competition'], "intermediate", 15)) {
						echo "<h4>Intermediate Section</h4>\n";
					} else {
						echo "<h4>Novice Section</h4>\n";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;
			
		case "Kilkhampton - November comp (6 and 8 bell)":
			$query = "SELECT event_id, competition FROM DRL_events WHERE competition LIKE 'Kilkhampton%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			echo "<h3>$year November Kilkhampton Competitions</h3>\n";
			while($event = mysqli_fetch_assoc($result)) {
				echo "<h4>".substr($event['competition'], 12)."</h4>";
				display_comp($cxn, $event['event_id'], true);
			}
			break;
			
		case "Littleham":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;
			
		case "Mid Devon Winter League":
			$query = "SELECT event_id, location, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition";
			echo "<h3>$year Mid Devon Winter League</h3>\n";
			$result = mysqli_query($cxn, $query);
			while($event = mysqli_fetch_assoc($result)) {
				switch(substr($event['competition'], -1)) {
					case "1":
						echo "<h4>Round 1 - held at $event[location]</h4>";
						break;
					case "2":
						echo "<h4>Round 2 - held at $event[location]</h4>";
						break;
					case "3":
						echo "<h4>Round 3 - held at $event[location]</h4>";
						break;
					case "4":
						echo "<h4>Round 4 - held at $event[location]</h4>";
						break;
					case "5":
						echo "<h4>Round 5 - held at $event[location]</h4>";
						break;
					case "6":
						echo "<h4>Round 6 - held at $event[location]</h4>";
						break;
				}
				display_comp($cxn, $event['event_id'], false);
			}
			break;
			
		case "Minor Final":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;

		case "Monkleigh":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;
			
		case "National":
			// Overall and handicap
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' OR competition LIKE 'Ben Isaac%' AND year=$year ORDER BY competition DESC";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			echo "<h3>$year National competitions</h3>\n";
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					if(substr_compare($event['competition'], "Ben Isaac", 0, 9)==0) {
						echo "<h4>Ben Isaac Shield (handicap event)</h4>";
					} else {
						echo "<h4>Overall results</h4>";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			}
			
			// 6 and 8 bell
			$query = "SELECT event_id, competition, location FROM DRL_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition ASC";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					echo "<h4>".loc_check($event['competition'], $event['location'], $year)."</h4>\n";
					display_comp($cxn, $event['event_id'], true);
				}
			} 
			break;

		case "NAIT Deaneries Gilbert Shield":
			// overall
			$query = "SELECT event_id, location, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition";
			echo "<h3>$year NAIT Deaneries Gilbert Shield</h3>\n";
			$result = mysqli_query($cxn, $query);
			while($event = mysqli_fetch_assoc($result)) {
				switch(substr($event['competition'], -1)) {
					case "l": // overall results
						echo "<h4>Overall results</h4>";
						break;
					case "1":
						echo "<h4>Round 1 - held at $event[location]</h4>";
						break;
					case "2":
						echo "<h4>Round 2 - held at $event[location]</h4>";
						break;
					case "3":
						echo "<h4>Round 3 - held at $event[location]</h4>";
						break;
				}
				display_comp($cxn, $event['event_id'], false);
			}
			break;

		case "Okehampton Deanery":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// intermediate and novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					if(substr_compare($event['competition'], "intermediate", 21)) {
						echo "<h4>Intermediate Section</h4>\n";
					} else {
						echo "<h4>Novice Section</h4>\n";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;
			
		case "Plymouth and Ivybridge Deaneries":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year Plymouth and Ivybrdige Deaneries (held at $event[location])</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// B and novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition DESC";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					if(substr_compare($event['competition'], "B section", 35)) {
						echo "<h4>B Section</h4>\n";
					} else {
						echo "<h4>Novice Section</h4>\n";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;

		case "Shebbear":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// junior section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					echo "<h4>Junior Section</h4>\n";
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;

		case "St Giles in the Wood":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;
			
		case "Stoke Gabriel":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// development section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Development Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;
			
		case "Stratton (6 and 8 bell)":
			$query = "SELECT event_id, competition FROM DRL_events WHERE competition LIKE 'Stratton%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			echo "<h3>$year Stratton Competitions</h3>\n";
			while($event = mysqli_fetch_assoc($result)) {
				echo "<h4>".substr($event['competition'], 9)."</h4>";
				display_comp($cxn, $event['event_id'], true);
			}
			break;
			
		case "Tamar Valley Guild (all sections)":
			// main sections - 6 bell, 8 bell and open
			$query = "SELECT event_id, location, competition FROM DRL_events WHERE competition LIKE 'Tamar Valley Guild%' AND year=$year ORDER BY competition";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h3>$year Tamar Valley Guild Competitions<br><span class=\"italic\">held at $event[location]</span></h3>";
			if($event['competition']=="Tamar Valley Guild") {
				echo "<h4>6 Bell</h4>";
			} else {
				echo "<h4>".substr($event['competition'],19)."</h4>";
			}
			
			display_comp($cxn, $event['event_id'], true);
			if(mysqli_num_rows($result)>1) {
				while($event = mysqli_fetch_assoc($result)) {
					if($event['competition']=="Tamar Valley Guild") {
						echo "<h4>6 Bell</h4>";
					} else {
						echo "<h4>".substr($event['competition'],19)."</h4>";
					}
					display_comp($cxn, $event['event_id'], true);
				}
			}
			// novice and junior novice sections
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE 'Tamar Valley Guild -%' AND year=$year ORDER BY competition DESC";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)){
					if(substr_compare($event['competition'], "novice", 21, 6)) {
						echo "<h4>Novice Section</h4>\n";
					} else {
						echo "<h4>Junior Novice Section</h4>\n";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;

		case "Tamar Valley Guild Winter Knockout Final":
			// main sections - 6 bell and open
			$query = "SELECT event_id, location, competition FROM DRL_events WHERE competition LIKE 'Tamar Valley K%' AND year=$year ORDER BY competition";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h3>$year Tamar Valley Guild Winter Knockout Final<br><span class=\"italic\">held at $event[location]</span></h3>\n";
			if($event['competition']!="Tamar Valley Knockout Final") {
				echo "<h4>6 Bell Open</h4>";
			} else {
				echo "<h4>6 Bell</h4>";
			}
			display_comp($cxn, $event['event_id'], true);
			if(mysqli_num_rows($result)>1) {
				while($event = mysqli_fetch_assoc($result)) {
					if($event['competition']!="Tamar Valley Knockout Final") {
						echo "<h4>6 Bell Open</h4>";
					} else {
						echo "<h4>6 Bell</h4>";
					}
					display_comp($cxn, $event['event_id'], true);
				}
			}
			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE 'Tamar Valley Guild K%' AND year=$year ORDER BY competition DESC";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;

		case "Tamar Valley Guild Winter Knockout":
			echo "<h3>$year Tamar Valley Guild Winter Knockout</h3>\n";
			echo "<p>There are multiple groups ringing in each round, each is presented as a separate event.</p>\n";
			$query = "SELECT event_id, location, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition";
			$result = mysqli_query($cxn, $query);
			while($event = mysqli_fetch_assoc($result)) {
				switch(substr($event['competition'], -1)) {
					case "4":
						echo "<h4>Round 4 - held at $event[location]</h4>";
						break;
					case "1":
						echo "<h4>Round 1 - held at $event[location]</h4>";
						break;
					case "2":
						echo "<h4>Round 2 - held at $event[location]</h4>";
						break;
					case "3":
						echo "<h4>Round 3 - held at $event[location]</h4>";
						break;
				}
				display_comp($cxn, $event['event_id'], false);
			}
			break;
			
		case "Torridge Valley Guild":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice and junior novice sections
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition DESC";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				while($event = mysqli_fetch_assoc($result)) {
					if(substr_compare($event['competition'], "junior", 24, 6)) {
						echo "<h4>Junior Novice Section</h4>\n";
					} else {
						echo "<h4>Novice Section</h4>\n";
					}
					display_comp($cxn, $event['event_id'], false);
				}
			} 
			break;
			
		case "Totnes Deanery Winter League":
			// overall
			$query = "SELECT event_id, location, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year ORDER BY competition";
			echo "<h3>$year Totnes Deanery Winter League</h3>\n";
			$result = mysqli_query($cxn, $query);
			while($event = mysqli_fetch_assoc($result)) {
				switch(substr($event['competition'], -1)) {
					case "l": // overall results
						echo "<h4>Overall results</h4>";
						break;
					case "1":
						echo "<h4>Round 1 - held at $event[location]</h4>";
						break;
					case "2":
						echo "<h4>Round 2 - held at $event[location]</h4>";
						break;
					case "3":
						echo "<h4>Round 3 - held at $event[location]</h4>";
						break;
					case "4":
						echo "<h4>Round 4 - held at $event[location]</h4>";
						break;
					case "5":
						echo "<h4>Round 5 - held at $event[location]</h4>";
						break;
					case "6":
						echo "<h4>Round 6 - held at $event[location]</h4>";
						break;
				}
				display_comp($cxn, $event['event_id'], false);
			}
			break;
			
		case "Wilf Edworthy":
			$query = "SELECT event_id, competition, location FROM other_events WHERE competition LIKE '$comp_name%' and year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h3>$year Wilf Edworthy Shield<br><span class=\"italic\">held at $event[location]</span></h3>\n";
			echo "<h4>Call Changes Section</h4>\n";
			display_comp($cxn, $event['event_id'], false);
			$event = mysqli_fetch_assoc($result);
			echo "<h4>Rounds Section</h4>\n";
			display_comp($cxn, $event['event_id'], false);
			break;
			
		case "Woodleigh Deanery":
			// main section
			$query = "SELECT event_id, location FROM DRL_events WHERE competition='$comp_name' AND year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result); 
			$content = "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			echo $content."<h4>Main Section</h4>";
			display_comp($cxn, $event['event_id'], true);

			// novice section
			$query = "SELECT event_id, competition FROM other_events WHERE competition LIKE '$comp_name%' AND year=$year";
			$result = mysqli_query($cxn, $query);
			if(mysqli_num_rows($result)>0) {
				$event = mysqli_fetch_assoc($result);
				echo "<h4>Novice Section</h4>\n";
				display_comp($cxn, $event['event_id'], false);
			} 
			break;
			
		case "Devon Ringers Council 10 Bell":
		case "Kilkhampton - John Cornish Shield":
			$query = "SELECT event_id, competition, location FROM other_events WHERE competition = '$comp_name' and year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			display_comp($cxn, $event['event_id'], false);
			break;
		
		default: // DRL results
			$query = "SELECT event_id, competition, location FROM DRL_events WHERE competition = '$comp_name' and year=$year";
			$result = mysqli_query($cxn, $query);
			$event = mysqli_fetch_assoc($result);
			echo "<h3>$year ".loc_check($comp_name, $event['location'], $year)."</h3>\n";
			display_comp($cxn, $event['event_id'], true);
			break;
	}
}

pick_comp($cxn, $_POST['comp'], $_POST['year']);
?>