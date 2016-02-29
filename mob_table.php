<?php
	include("allpages/dbinfo.inc");
	$cxn = mysqli_connect($host,$user,$passwd,$database) or die ("Database not available at the moment\nPlease try again later");

	$flag = $_GET['flag'];
	if(date("m")<=4) {
		$this_year = date("Y")-1;
	} else {
		$this_year = date("Y");
	}

	if($flag==1) {
		$query = "SELECT team, ranking FROM DRL_all_time_table WHERE year=$this_year AND no_of_comps>=5 ORDER BY ranking DESC, fault_diff DESC";
	} else {
		$query = "SELECT team, ranking FROM DRL_all_time_table WHERE year=$this_year ORDER BY ranking DESC, fault_diff DESC";
	}
	$result = mysqli_query($cxn, $query);
	
function quick_table($flag, $result) {
	if($flag==0) {
		echo "<p class=\"head_3\">All comers table</p>\n";
	} else {
		echo "<p class=\"head_3\">5 or more table</p>\n"; 
	}
	echo "<ol>\n";
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<li>$row[team] - ";
		printf ("%6.2f</li>\n", $row['ranking']);
	}
	echo "</ol>\n";
}
?>

<html>
<head>
  <title>James Kerslake's Devon Style Bellringing site - Mobile Phone tables Page</title>
  <link href="new.css" type="text/css" rel="stylesheet">
</head>
<body>
<p class="head_1">Call change ringing resources</p>
<p class="head_2">The Mobile Page - table</p>
<p class="bold" style="text-align:center;"><a href="mobile.php">Mobile Home Page</a></p>
<?php quick_table($flag, $result); ?>
</body>
</html>

