<?php
	// website header files 
	include("../allpages/dbinfo.inc");
?>
<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - DRL: Acknowledgements</title>
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
if(date("m")<=4) {
	echo "<div id=\"sub_unselected\"><a class=\"sub_unselect\" href=\"drl_tables.php?season=".(date("Y")-1)."\">Full Table</a></div>";
} else {
	echo "<div id=\"sub_unselected\"><a class=\"sub_unselect\" href=\"drl_tables.php?season=".date("Y")."\">Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_selected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id="main_content">
  <h2>Devon Ringing Archive: Acknowledgements</h2>
  
  <p>Given that this site appears to have become quite popular, and also that I am now getting on with researching out results etc. from before the year 2000, I felt I should acknowledge and thank the many ringers that have helped me expand the database. However, the first person I need to thank above all others is my mother, Sue, for introducing me to ringing in the first place and I also need to thank David Trout for actually teaching me to ring - from such small acorns... Anyway the main thrust of the page is to give a list of the people who have knowingly, or otherwise, contributed results or team lists to the site. I definitely wouldn't have been able to compile the archive without their help - however unwitting it may have been! I apologise if I've forgotten anyone, but one can only remember so much.</p>
  <h3>People who have provided results or team lists:</h3>
  <p>Scott Adams<br>
  Ian Avery<br>
  Stuart Bennie<br>
  Chris Clayton<br>
  Jereme Darke<br>
  John Enderson<br>
  Steve Herniman<br>
  Graham Pascoe<br>
  Bob Pengelly<br>
  Graham Sharland<br>
  Ryan Trout<br>
  Clive Ward<br>
  Mervyn Way<br>
  Mike Webster</p>
</div>
</body>
</html>
