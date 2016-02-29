<?php
	// website header files 
	include("allpages/header.inc"); 
	include("allpages/dbinfo.inc");
	$cxn = mysqli_connect($host, $user, $passwd, $database);
	
	$query = "SELECT rant_code, rant_date FROM rants ORDER BY rant_date DESC LIMIT 0, 2";
	$result = mysqli_query($cxn, $query);

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
}
?>
<title>James Kerslake's Devon-style bellringing site</title>
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
<div id="main_content" style="padding-bottom:2px; margin-bottom:5px">
<p>Welcome everyone to the site! On this site you can find resources for Devon-style call change ringing. This is where you can find the official pages of the completely unofficial Devon Ringing League, which I invented a few years back, and the unofficial pages of the official Devon Association of Ringers Ringing Ladder. The tabs above give you links to these areas: the league tab for the DRL, and the ladder tab for Devon Association Ringing Ladder. I try to update this site at least once a month (during the summer at least), especially the league and ladder pages as this is where most of the action is. When I see, or come up with, new compositions I'll add them when I get the chance but they may take a bit more time, what with typing them up and then fiddling about to make them into pdfs...<br />Enjoy! <br />&nbsp;&nbsp;&nbsp;&nbsp;James Kerslake<br /><br />Comments, feedback and suggestions? Email me: <a href="mailto:webmaster@myconid.co.uk">webmaster@myconid.co.uk</a></p>

<?php
	while($row = mysqli_fetch_assoc($result)) {
		echo "<h3>Update! ".date_display($row['rant_date'])."</h3>\n";
		echo $row['rant_code'];
	}	
?>


</div>
</body>
</html>
