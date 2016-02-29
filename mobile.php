<?php
	include("allpages/dbinfo.inc");
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");

function quick_table($cxn) {
	if(date("m")<=4) {
		$this_year = date("Y")-1;
	} else {
		$this_year = date("Y");
	}
	$query = "SELECT team, ranking FROM DRL_all_time_table WHERE year=$this_year AND no_of_comps>=5 ORDER BY ranking DESC, fault_diff DESC LIMIT 0,10";
	$result = mysqli_query($cxn, $query);
	echo "<ol>\n";
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<li>$row[team] - ";
		printf ("%.2f</li>\n", $row['ranking']);
	}
	echo "</ol>\n";
}

function results_list($cxn) {
	$query = "SELECT * FROM DRL_events ORDER BY event_id DESC LIMIT 0,20";
	$result = mysqli_query($cxn, $query);
	echo "<ul>\n";
	while($row = mysqli_fetch_assoc($result)) {
		echo "<li class=\"nobullet\"><a href=\"mob_results.php?id=$row[event_id]\">$row[competition] $row[year]</a></li>\n";
	}
	echo "</ul>\n";
}

?>

<html>
<head>
  <title>James Kerslake's Devon Style Bellringing site - Mobile Phone Page</title>
  <link href="new.css" type="text/css" rel="stylesheet">
</head>
<body>
<p class="head_1">Call change ringing resources</p>
<p class="head_2">The Mobile Page</p>
<p class="bold" style="text-align:center;"><a href="/index.html">Main Site</a></p>
<p class="text">Hello, you've reached the stripped down page on my site, designed for mobile phones. I'll be putting here results and the table for the DRL, as I'm fed up of trying to work out how to get the results on to my phone...</p>
<p class="head_2">Condensed table</p>
<p class="text">Displaying only the top ten, team name and ranking!</p>
<?php quick_table($cxn); ?>
<p class="text"><a href="mob_table.php?flag=1">5 or more table</a> | <a href="mob_table.php?flag=0">all-comers table</a></p>
<p class="head_2">Recent results</p>
<p class="text">This is the last 20 results entered:</p>
<?php results_list($cxn); ?>
</body>
</html>

