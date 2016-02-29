<?php
	// website header files 
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
	include("drl_stat_queries.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - results archive</title>
<script src="/allpages/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="/allpages/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new2.css">

<style type="text/css">
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
if(date("m")<=4) {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".($latest_year-1)."'>Full Table</a></div>";
} else {
	echo "<div id='sub_unselected'><a class='sub_unselect' href='drl_tables.php?season=".$latest_year."'>Full Table</a></div>";
}
?><div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
<?php
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");
	
	// display the page header
	$query = "SELECT event_id, `year`, competition, location FROM DRL_events WHERE event_id='$_GET[event_id]'";
	$result = mysqli_query($cxn,$query) or die ("Could not find competition ");
	$row = mysqli_fetch_assoc($result);
	$event = $row[event_id];
	// make title string
	$title_string = loc_check($row['competition'], $row['location'], $row['year']);
	
	echo "	<h2>Results Archive<br>$row[year] $title_string</h2>\n";
	echo "	<p class=\"center\"><a href=\"drl_archive2.php\">Archive main page</a></p>\n";
	
	// get results
	$query = "SELECT position, team, faults FROM DRL_results WHERE event_id='$event' ORDER BY position";
	$result = mysqli_query($cxn,$query) or die ("Could not find table");
?>
	<table style="margin:0 auto">
    	<tr>
        	<th>&nbsp;</th>
            <th>Team</th>
            <th>Faults</th>
        </tr>
<?php
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr>\n<td class=\"right\">$row[position]</td>\n<td>$row[team]</td>\n<td class=\"right\">$row[faults]</td>\n</tr>\n";
	}
?>
</table>
<p class="center"><a href="drl_archive.php">Archive main page</a></p>


</div>
</body>
</html>
