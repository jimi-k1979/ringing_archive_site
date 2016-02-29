<?php
	// website header files 
	include("allpages/header.inc"); 
	include("allpages/dbinfo.inc");
// date_display($date) converts a numerical dates in the format 
// YYYY-MM-DD into words in the form "nth Month Year" and returns them as a string
function date_display($date) {
	$return_string = "";
	
	// month name
	switch(substr($date, 5, 2)) {
		case '01':
			$return_string .= "January ";
			break;
		case '02':
			$return_string .= "February ";
			break;
		case '03':
			$return_string .= "March ";
			break;
		case '04':
			$return_string .= "April ";
			break;
		case '05':
			$return_string .= "May ";
			break;
		case '06':
			$return_string .= "June ";
			break;
		case '07':
			$return_string .= "July ";
			break;
		case '08':
			$return_string .= "August ";
			break;
		case '09':
			$return_string .= "September ";
			break;
		case '10':
			$return_string .= "October ";
			break;
		case '11':
			$return_string .= "November ";
			break;
		case '12':
			$return_string .= "December ";
			break;
	}

	// get day
	switch(substr($date, -2)) {
		case '01':
			$return_string .= "1st ";
			break;
		case '02':
			$return_string .= "2nd ";
			break;
		case '03':
			$return_string .= "3rd ";
			break;
		case '04':
			$return_string .= "4th ";
			break;
		case '05':
			$return_string .= "5th ";
			break;
		case '06':
			$return_string .= "6th ";
			break;
		case '07':
			$return_string .= "7th ";
			break;
		case '08':
			$return_string .= "8th ";
			break;
		case '09':
			$return_string .= "9th ";
			break;
		case '10':
			$return_string .= "10th ";
			break;
		case '11':
			$return_string .= "11th ";
			break;
		case '12':
			$return_string .= "12th ";
			break;
		case '13':
			$return_string .= "13th ";
			break;
		case '14':
			$return_string .= "14th ";
			break;
		case '15':
			$return_string .= "15th ";
			break;
		case '16':
			$return_string .= "16th ";
			break;
		case '17':
			$return_string .= "17th ";
			break;
		case '18':
			$return_string .= "18th ";
			break;
		case '19':
			$return_string .= "19th ";
			break;
		case '20':
			$return_string .= "20th ";
			break;
		case '21':
			$return_string .= "21st ";
			break;
		case '22':
			$return_string .= "22nd ";
			break;
		case '23':
			$return_string .= "23rd ";
			break;
		case '24':
			$return_string .= "24th ";
			break;
		case '25':
			$return_string .= "25th ";
			break;
		case '26':
			$return_string .= "26th ";
			break;
		case '27':
			$return_string .= "27th ";
			break;
		case '28':
			$return_string .= "28th ";
			break;
		case '29':
			$return_string .= "29th ";
			break;
		case '30':
			$return_string .= "30th ";
			break;
		case '31':
			$return_string .= "31st ";
			break;
	}

	return $return_string .= substr($date, 0, 4);
}// */

	$cxn = mysqli_connect($host, $user, $passwd, $database);
	
	$this_year = date("Y");

?>
<title>James Kerslake's Devon-style bellringing site</title>
<link href="/allpages/jquery-ui-1.8.6.custom.css" type="text/css" rel="stylesheet" />
<script src="/allpages/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="/allpages/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#tabs').tabs();
});
</script>
<style type="text/css">
body {
background:#000099;
}
</style>

</head>

<body>
<!-- Banner at page top -->
<div id='top_banner' style="background:white;">
<div id='top_left'></div>
<div id='top_center' class='head_1'>Call change ringing resources</div>
<div id='top_right'></div>
</div>

<!-- Menu Banner -->
<div id='menu_banner' style="background:url(/allpages/menubar_bg2.png)">
<div id='menu_container'>
<div id='sl_end' style="background:url(/allpages/sl_end2.png)"></div>
<div id='selected_tab' style="background:url(/allpages/selected_bg2.png)"><a class='selected' href='/index.php'>Home</a></div>
<div id='su_split' style="background:url(/allpages/su_split2.png)"></div>
<div id='unselected_tab' style="background:url(/allpages/unselected_bg2.png)"><a class='unselected' href='/league/index.php'>League</a></div>
<div id='uu_split' style="background:url(/allpages/uu_split2.png)"></div>
<div id='unselected_tab' style="background:url(/allpages/unselected_bg2.png)"><a class='unselected' href='/ladder/index.html'>Ladder</a></div>
<div id='uu_split' style="background:url(/allpages/uu_split2.png)"></div>
<div id='unselected_tab' style="background:url(/allpages/unselected_bg2.png)"><a class='unselected' href='/compositions/index.php'>Compositions</a></div>
<div id='ur_end' style="background:url(/allpages/ur_end2.png)"></div>
</div>
</div>


<!-- Sub menu -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:160px;">
<div id="sub_selected"><a class="sub_select" href="index.php">Home</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="archive.php">News Archive</a></div>
</div>
</div>

<!-- content -->
<div id="main_content" style="background:white; padding-bottom:5px 	">
<h2>News Archive</h2>
<p>The tabs below give the news updates from the year selected.</p>
<div id="tabs" style="margin:5px">
<ul>
<?php 
	for($i=2008; $i<=$this_year; $i++) {
		echo "<li><a href=\"#y$i\">$i</a></li>\n";
	}
?>
</ul>
<?php
	for($i=2008; $i<=$this_year; $i++) {
		$query = "SELECT rant_code, rant_date FROM rants WHERE rant_date >= \"$i-01-01\" AND rant_date <= \"$i-12-31\" ORDER BY rant_date DESC";
		$result = mysqli_query($cxn, $query);
		echo "<div id=\"y$i\">\n";
		if(mysqli_num_rows($result)==0) {
			echo "<p>There asre no updates from this year yet!</p>\n";
		} else {
			while($row = mysqli_fetch_assoc($result)) {
				echo "<h3>Update! ".date_display($row['rant_date'])."</h3>\n";
				echo $row['rant_code'];
			}
		}
		echo "</div>\n";
	}
?>


</div>
</div>
</body>
</html>
