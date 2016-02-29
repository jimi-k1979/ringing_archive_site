<?php
	// website header files 
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - Devon Ringing League homepage</title>
<script src="/allpages/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="/allpages/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new2.css">

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

<!-- Sub menu -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_select" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
if(date("m")<=4) {
	$year = $latest_year-1;
} else {
	$year = $latest_year;
}
echo "<div id=\"sub_unselected\"><a class=\"sub_unselect\" href=\"drl_tables.php?season=$year\">Full Table</a></div>";

?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	if(date("m")>=6) {
		if($year==$latest_year) {
			$no_of_comps = 5;
            $text = "<p>It's after the Major Final and the table should now be for teams that have rung in 5 or more competitions, assuming I've coded everything correctly.</p>";
		} else {
			$no_of_comps = 3;
            $text = "<p>I'm not entirely sure what is happening right now (as I got myself knotted up in the code), but the table has teams who have rung in 3 or more competitions on it. Whether that's right or not is anyone's guess...</p>";
		}
	} else {
        if($year==$latest_year) {
            $no_of_comps = 3;
            $text = "<p>The table below reflects who's making the early running and, as is normal for this time of year, contains teams that have rung in 3 or more competitions</p>";
        } else {
            $no_of_comps = 5;
            $text = "<p>We're in the off season right now, so the table shows last year's winners and all the other teams that rang in 5 or more competitions.</p>";
        }
	}
?>

<!-- content -->
<div id="main_content">
	<h2>Devon Ringing League</h2>
    
    <p>These pages will not only give you the full current DRL table, but also the archive of previous tables, stats and records for the league, and the method used to calculate the ratings. The table below is the main table, qualification is teams which have rung in <?php echo $no_of_comps;?> or more competitions. A full table, including a list of all the competitions used to work out the league rankings, is available by clicking on the link just above.</p>

  <p><span class='bold'>This week's interesting but ultimately pointless statistic is...</span><br>
...The total number of faults scored in the all-time 100 lowest scores (as of 13th May 2013) is 611 5/6. This is lower than the sum of the two highest all time scores which is 677 1/2. Given that faults don't come in sixths I feel I should explain that a third plus a half equals 5/6 so you know where that has come from.</p>

<?php
    echo $text;
	echo "<h3>Current DRL $year Table</h3>\n";

	// get and store table in a $results[column][index] array, there are $tot_rows rows
	$query = "SELECT team, no_of_comps, ranking, fault_diff FROM DRL_all_time_table WHERE year='$year' AND no_of_comps >= $no_of_comps ORDER BY ranking DESC, fault_diff DESC";
	$result = mysqli_query($cxn,$query) or die ("Could not find table");
	
	$i = 1;
	$output = "<table>\n<tr>\n<th>&nbsp;</th>\n<th>Team</th>\n<th>C</th>\n<th>Rank</th>\n<th>+ / -</th>\n</tr>\n";
	while($row = mysqli_fetch_assoc($result)) {
		$output .= " <tr>\n<td class=\"bold right\">$i</td>\n<td>$row[team]</td>\n<td class=\"right\">$row[no_of_comps]</td>\n<td class=\"bold right\">".number_format($row['ranking'],2)."</td>\n<td class=\"right\">".number_format($row['fault_diff'],2)."</td>\n</tr>\n";
		$i++;
	}
	$output .= "</table>\n";
	echo $output;

?>
<p>&nbsp;</p>
</div>
</body>
</html>