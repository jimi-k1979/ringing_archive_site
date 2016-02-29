<?php
	include("allpages/dbinfo.inc");
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");

	$id = $_GET['id'];
	$query = "SELECT competition, year FROM DRL_events WHERE event_id=$id";
	$result = mysqli_query($cxn, $query);
	$event = mysqli_fetch_assoc($result);
	
function results_list($cxn, $id) {
	$query = "SELECT position, team, faults FROM DRL_results WHERE event_id=$id ORDER BY position ASC";
	$result = mysqli_query($cxn, $query);
	echo "<p class=\"text\">\n";
	while($row = mysqli_fetch_assoc($result)) {
		printf("%2d - %6.2f ", $row['position'], $row['faults']);
		echo "$row[team]<br>\n";
	}
	echo "</p>\n";
}

?>

<html>
<head>
  <title>James Kerslake's Devon Style Bellringing site - Mobile Phone Results Page</title>
  <link href="new.css" type="text/css" rel="stylesheet">
</head>
<body>
<p class="head_1">Call change ringing resources</p>
<p class="head_2">The Mobile Page - Results</p>
<p class="bold" style="text-align:center;"><a href="mobile.php">Mobile Home Page</a></p>
<?php echo "<p class=\"head_3\">$event[competition] $event[year]</p>\n"; 
results_list($cxn, $id); ?>
</body>
</html>

