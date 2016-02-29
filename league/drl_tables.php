<?php
	// website header files 
	include("../allpages/dbinfo.inc");
	include("drl_stat_queries.php");
    include("../allpages/variables.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - DRL table</title>
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
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
// make the correct entry for current table
if(date("m")<=4) {
	$this_year = $latest_year-1;
} else {
	$this_year = $latest_year;
}
if($_GET[season] == $this_year) {
	echo "<div id=\"sub_selected\"><a class=\"sub_select\" href=\"drl_tables.php?season=$this_year\">Full Table</a></div>
<div id=\"sub_split\"></div>
<div id=\"sub_unselected\"><a class=\"sub_unselect\" href=\"drl_archive2.php\">Archive</a></div>
<div id=\"sub_split\"></div>";
} else {
	echo "<div id=\"sub_selected\"><a class=\"sub_unselect\" href=\"drl_tables.php?season=$this_year\">Full Table</a></div>
<div id=\"sub_split\"></div>
<div id=\"sub_unselected\"><a class=\"sub_select\" href=\"drl_archive2.php\">Archive</a></div>
<div id=\"sub_split\"></div>";
}
?>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id="main_content">

<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
	// display the year header
	
	echo "<h2>DRL $_GET[season] season</h2>\n";
	echo "<p class=\"center\" style=\"margin:10px\"><a href=\"drl_archive2.php\">Archive main page</a></p>\n";

	
	// get and display the competition list
	$output = "<p class=\"small\"><span class=\"italic\">Competitions included:</span> ";
	$query = "SELECT competition, location FROM DRL_events WHERE year='$_GET[season]'";
	$result = mysqli_query($cxn,$query) or die ("Could not get competition list");
	$comps = mysqli_num_rows($result);
	for($i=0;$i<($comps-1);$i++) {
		$row = mysqli_fetch_assoc($result);
		$output .= loc_check($row['competition'], $row['location'], $_GET['season']).", ";
	}
	$row = mysqli_fetch_assoc($result);
	$output .= loc_check($row['competition'], $row['location'], $_GET['season'])."</p>";
	echo $output;

	$query = "SELECT team, no_of_comps, ranking, fault_diff FROM DRL_all_time_table WHERE year='$_GET[season]' ORDER BY ranking DESC, fault_diff DESC";
	
	$result = mysqli_query($cxn,$query) or die ("Could not find table");
	$output = "<table style=\"margin: 10px auto\">\n<tr>\n<th>Team</th>\n<th>C</th>\n<th>Rank</th>\n<th>+ / -</th>\n</tr>\n";
	while($row=mysqli_fetch_assoc($result)) {
		$output	.= "<tr>\n<td>".team_check($row['team'])."</td>\n<td class=\"right\">$row[no_of_comps]</td>\n<td class=\"right\">".number_format($row['ranking'], 2)."</td>\n<td class=\"right\">".number_format($row['fault_diff'], 2)."</td>\n</tr>";
	}
	$output .= "</table>\n";
	echo $output;

?>
<p class="center"><a href="drl_archive2.php">Archive main page</a></p>
</div>
</body>
</html>
