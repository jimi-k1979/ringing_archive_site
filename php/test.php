<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
?>
<title>James Kerslake's Devon-style bellringing site</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/ladder_banner.inc"); ?>

<!-- Sub menu -->
<div id='sub_menu_banner'>
<div id='sub_menu_container' style='width:545px;'>
<div id='sub_unselected'><a class='sub_unselect' href='../../index.html'>Ladder Home</a></div>
<div id='sub_split'></div>
<div id='sub_selected'><a class='sub_unselect' href='2009b.html'>Fixtures &amp; Results</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='../tables/2009b.html'>Tables</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_select' href='../index.html'>Archive</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='#'>Statistics</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='../../howitworks.html'>How it Works</a></div>
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
	
	echo "	<div class='head_2'>Ladder Results: $row[description]</div>\n";
	echo "	<div class='text' style='text-align:center;'><a href='#'>Archive main page</a> | <a href='#'>Results index</a></div>\n";
	echo "	<div class='text' style='text-align:center;'><span class='bold'>Jump to:</span> <a href='test.php?season=$_GET[season]&section=A'>A Section</a> | <a href='test.php?season=$_GET[season]&section=B'>B Section</a> | <a href='test.php?season=$_GET[season]&section=R'>Rounds Section</a></div>\n";
	
	// get and display the links header
	$query = "SELECT description FROM ladder_sections WHERE section_id='$_GET[section]'";
	$result = mysqli_query($cxn,$query) or die ("Could not find section");
	$row = mysqli_fetch_assoc($result);
	echo "	<div class='head_3'>$_GET[section] Section - $row[description]</div>\n";

	// get and store in a $results[column][index] array, there are $tot_results results
	$query = "SELECT lad_group, win_team, win_faults, lose_team, lose_faults FROM lad_res_classified WHERE season='$_GET[season]' AND section='$_GET[section]' ORDER BY lad_group";
	$result = mysqli_query($cxn,$query) or die ("Could not find results");
	
	$tot_results=0;
	$tot_groups=0;
	$groups[0]='0';
	while($row = mysqli_fetch_assoc($result)) {
		
		foreach($row as $key => $value) {	
			switch($key) {
				case "lad_group":
					$results[group][$tot_results]=$value;
						// keep a record of the groups in the groups array
						if($groups[0]=='0') {
							$groups[0]=$value;
							$tot_groups++;
						} else if($value!=$groups[$tot_groups-1]) {
							$groups[$tot_groups]=$value;
							$tot_groups++;
						}
					break;
				case "win_team":
					$results[win_team][$tot_results]=$value;
					break;
				case "win_faults":
					$results[win_faults][$tot_results]=$value;
					break;
				case "lose_team":
					$results[lose_team][$tot_results]=$value;
					break;
				case "lose_faults":
					$results[lose_faults][$tot_results]=$value;
					break;
			}
		}
		$tot_results++;
	}
	
	$j=0;

	// fill the page - looping over all $tot_results
	for($i=0;$i<$tot_results;$i++) {
		if($groups[$j]==$results[group][$i]) {
			echo "	<div class='head_4'>Group $groups[$j]</div>\n";
			echo "\n	<div id='table_container' style='width:716px;height:130px;'>
	<div id='table_header'>
		<div id='width_350px_head'>Winning Team</div>
		<div id='width_350px_head'>Losing Team</div>
	</div>
	<div id='table_body'>
		<div id='width_300px'>";
			$j++;
			$last=$j-1;
			
			// teams	
			for($team_loop=0;$team_loop<$tot_results;$team_loop++) {
				if($results[group][$team_loop]==$groups[$last]) {
					echo $results[win_team][$team_loop]."<br />\n";
				}
			} 
			echo "		</div>\n		<div id='width_50px'>";
			
			// faults
			for($fault_loop=0;$fault_loop<$tot_results;$fault_loop++) {
				if($results[group][$fault_loop]==$groups[$last]) {
					printf("%.2f<br />",$results[win_faults][$fault_loop]); // display 2dp only
				}
			}
			echo "		</div>\n		<div id='width_300px'>";

			// teams	
			for($team_loop=0;$team_loop<$tot_results;$team_loop++) {
				if($results[group][$team_loop]==$groups[$last]) {
					echo $results[lose_team][$team_loop]."<br />\n";
				}
			} 
			echo "		</div>\n		<div id='width_50px'>";
			
			// faults
			for($fault_loop=0;$fault_loop<$tot_results;$fault_loop++) {
				if($results[group][$fault_loop]==$groups[$last]) {
					printf("%.2f<br />",$results[lose_faults][$fault_loop]); // display 2dp only
				}
			}
			echo "		</div>\n		</div>\n	</div>";

		}
	}

	echo "	<div class='text' style='text-align:center;'><a href='#'>Archive main page</a> | <a href='#'>Results index</a></div>\n";
	echo "	<div class='text' style='text-align:center;'><span class='bold'>Jump to:</span> <a href='test.php?season=$_GET[season]&section=A'>A Section</a> | <a href='test.php?season=$_GET[season]&section=B'>B Section</a> | <a href='test.php?season=$_GET[season]&section=R'>Rounds Section</a></div>\n";

?>



</div>
</body>
</html>
