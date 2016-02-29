<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
	
function table_plot($result, $cxn, $season) {
	$tot_rows=0;
	$tot_groups=0;
	$groups[0]='0';
	$tie_check = 0;
	while($row = mysqli_fetch_assoc($result)) {
	
		foreach($row as $key => $value) {	
			switch($key) {
				case "lad_group":
					$results[group][$tot_rows]=$value;
						// keep a record of the groups in the groups array
						if($groups[0]=='0') {
							$groups[0]=$value;
							$tot_groups++;
						} else if($value!=$groups[$tot_groups-1]) {
							$groups[$tot_groups]=$value;
							$tot_groups++;
						} else { // set flag for checking for ties in the table
							$tie_check = 1;
						}
					break;
				case "team":
					$results[team][$tot_rows]=$value;
					break;
				case "played":
					$results[played][$tot_rows]=$value;
					break;
				case "points":
					$results[points][$tot_rows]=$value;
					break;
			}
		}
		// check for ties and sort teams correctly
		if($tie_check == 1) {		
			if($results[points][$tot_rows]==$results[points][$tot_rows-1]) {
				// points are equal - find out who won the match between them
				$query="SELECT win_team, lose_team, win_faults, lose_fualts FROM lad_res_classified WHERE season=$season AND (win_team=".$results[team][$tot_rows-1]." OR win_team=".$results[team][$tot_rows].")";
				if($row=mysqli_query($cxn, $query)) {
					$tie_break=mysqli_fetch_assoc($row);
					// if the fixture was a tie the database already orders the teams correctly,
					// otherwise the winning team gets placed above the losing team.
					if($tie_break[win_faults]!=$tie_break[lose_faults]) {
						// the query returns a result - place the winning team above the losing team
						$results[team][$tot_rows-1]=$tie_break[win_team];
						$results[team][$tot_rows]=$tie_break[lose_team];
					}
				}
			}	
			$tie_check = 0; // reset flag
		}
		$tot_rows++;
	}
	
	$j=0;

	// fill the page - looping over all $tot_results
	for($i=0;$i<$tot_rows;$i++) {
		if($groups[$j]==$results[group][$i]) {
			echo "	<div class='head_4'>Group $groups[$j]</div>\n";
			echo "\n	<div id='table_container' style='width:412px;height:100px;'>
	<div id='table_header'>
		<div id='width_300px_head'>Team</div>
		<div id='width_50px_head'>R</div>
		<div id='width_50px_head'>Pts</div>
	</div>
	<div id='table_body'>
		<div id='width_300px'>";
			$j++;
			$last=$j-1;
			
			// teams	
			for($team_loop=0;$team_loop<$tot_rows;$team_loop++) {
				if($results[group][$team_loop]==$groups[$last]) {
					echo $results[team][$team_loop]."<br />\n";
				}
			} 
			echo "		</div>\n		<div id='width_50px'>";
		
			// played
			for($played_loop=0;$played_loop<$tot_rows;$played_loop++) {
				if($results[group][$played_loop]==$groups[$last]) {
					echo $results[played][$played_loop]."<br />\n";
				}
			}
			echo "		</div>\n		<div id='width_50px'>";

			// points	
			for($points_loop=0;$points_loop<$tot_rows;$points_loop++) {
				if($results[group][$points_loop]==$groups[$last]) {
					echo $results[points][$points_loop]."<br />\n";
				}
			} 		
			echo "		</div>\n		</div>\n	</div>";

		}
	}
}	
?>
<title>James Kerslake's Devon-style bellringing site - Ladder archive</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/ladder_banner.inc"); ?>

<!-- Sub menu -->
<div id='sub_menu_banner'>
<div id='sub_menu_container' style='width:545px;'>
<div id='sub_unselected'><a class='sub_unselect' href='index.html'>Ladder Home</a></div>
<div id='sub_split'></div>
<div id='sub_selected'><a class='sub_unselect' href='current_fixtures.html'>Fixtures &amp; Results</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='lad_tables.php?season=2009b'>Tables</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_select' href='lad_archive.php'>Archive</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='#'>Statistics</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='howitworks.html'>How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
	// get and display the season header
	$query = "SELECT description FROM ladder_seasons WHERE season_id='$_GET[season]'";
	$result = mysqli_query($cxn,$query) or die ("Could not find season");
	$row = mysqli_fetch_assoc($result);
	
	echo "	<div class='head_2'>Ladder Tables: $row[description]</div>\n";
	echo "	<div class='text' style='text-align:center;'><a href='lad_archive.php'>Archive main page</a>\n";

  if($_GET[section]) {
		// get and display the links header
		$query = "SELECT description FROM ladder_sections WHERE section_id='$_GET[section]'";
		$result = mysqli_query($cxn,$query) or die ("Could not find section");
		$row = mysqli_fetch_assoc($result);
		echo "	<div class='head_3'>$_GET[section] Section - $row[description]</div>\n";
	
		// get and store in a $results[column][index] array, there are $tot_rows rows
		$query = "SELECT lad_group, team, played, points FROM lad_all_tables WHERE season='$_GET[season]' AND section='$_GET[section]' ORDER BY lad_group, points DESC";
		$result = mysqli_query($cxn,$query) or die ("Could not find tables");
	
		table_plot($result, $cxn, $_GET[season]);
		echo "	<div class='text' style='text-align:center;'><a href='lad_archive.php'>Archive main page</a>\n";
	} else {
		// loop through the sections
		for($i=0;$i<3;$i++) {
			// get and display the header
			if($i==0) {
				$query = "SELECT description FROM ladder_sections WHERE section_id='A'";
				$section = "A";
			} else if ($i==1) {
				$query = "SELECT description FROM ladder_sections WHERE section_id='B'";
				$section = "B";
			} else { // ($i==2)
				$query = "SELECT description FROM ladder_sections WHERE section_id='R'";
				$section = "Rounds";
			}
			$result = mysqli_query($cxn,$query) or die ("Could not find section");
			$row = mysqli_fetch_assoc($result);
			echo "	<div class='head_3'>$section Section - $row[description]</div>\n";

			// get and store in a $results[column][index] array, there are $tot_rows rows
			$query = "SELECT lad_group, team, played, points FROM lad_all_tables WHERE season='$_GET[season]' AND section='$section' ORDER BY lad_group, points DESC";
			$result = mysqli_query($cxn,$query) or die ("Could not find tables");
	
			table_plot($result, $cxn, $_GET[season]);
		}
		echo "	<div class='text' style='text-align:center;'><a href='lad_archive.php'>Archive main page</a>\n";
	}
?>



</div>
</body>
</html>
