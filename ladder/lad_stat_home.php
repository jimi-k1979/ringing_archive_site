<?php
	// website header files 
	include("../allpages/header.inc"); 
	include("../allpages/dbinfo.inc");
?>
<title>James Kerslake's Devon-style bellringing site - DRL stats</title>

<!-- banner and main menu for ladder pages -->
<?php include("../allpages/ladder_banner.inc"); ?>

<!-- Sub menu -->
<div id='sub_menu_banner'>
<div id='sub_menu_container' style='width:545px;'>
<div id='sub_unselected'><a class='sub_unselect' href='index.html'>Ladder Home</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='current_fixtures.html'>Fixtures &amp; Results</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='lad_tables.php?season=2009b'>Tables</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='lad_archive.php'>Archive</a></div>
<div id='sub_split'></div>
<div id='sub_selected'><a class='sub_select' href='lad_stat_home.php'>Statistics</a></div>
<div id='sub_split'></div>
<div id='sub_unselected'><a class='sub_unselect' href='howitworks.html'>How it Works</a></div>
</div>
</div>

<!-- content -->
<div id='main_content'>
	<div class='head_2'>Ringing Ladder Statistics and Records</div>
  <div class="text">On these pages I have compiled some assorted records and statistics using the ladder tables and the results of the fixtures which make up those tables. If there are any ideas or requests for further statistics let me know, either by email (<a href="mailto:webmaster[at]myconid[dot]co[dot]uk">by clicking here</a>) or in person, and I'll try my best to come up with the goods.</div>

	 <div class="head_3">Collected records</div>
	
<div style="height:250px; float:none;">
	<div id="table_container" style="width:912px">
		<div id="table_header">
			<div id="width_300px_head"><a href="lad_stats.php?firstat=27&amp;lastat=9" style="color:#fff">Ladder records</a></div>
  		<div id="width_300px_head"><a href="lad_stats.php?firstat=10&amp;lastat=16" style="color:#fff">Fixture records</a></div>
			<div id="width_300px_head"><a href="lad_stats.php?firstat=17&amp;lastat=26" style="color:#fff">Team records</a></div>
		</div>
		
		<div id="table_body">	
			<div id="width_300px" style="text-align:center">
				<a href="drl_stats.php?firstat=27&amp;lastat=33">All Ladder records</a><br /> <!-- send firstat and lastat -->
		    <a href="drl_stats.php?firstat=27&amp;lastat=29">Section winners</a><br /> <!-- send firstat only -->
  		  <a href="drl_stats.php?firstat=30&amp;lastat=32">Promoted and relegated</a><br />
    		<a href="drl_stats.php?firstat=33">Teams scoring 9 points in a season</a>
			</div>
	
			<div id="width_300px" style="height:inherit;text-align:center">
				<a href="drl_stats.php?firstat=34&amp;lastat=37">All fixture records</a><br />
		    <a href="drl_stats.php?firstat=34">Most used towers</a><br />
    		<a href="drl_stats.php?firstat=35">Most used judges</a><br />
    		<a href="drl_stats.php?firstat=36">Largest victory margins</a><br />
				<a href="drl_stats.php?firstat=37">Smallest victory margins</a>
			</div>
			
			<div id="width_300px" style="height:inherit;text-align:center">
				<a href="drl_stats.php?firstat=38&amp;lastat=41">All team records</a><br />
				<a href="drl_stats.php?firstat=38">Lowest fault scores</a><br />
				<a href="drl_stats.php?firstat=39">Highest fault scores</a><br />
				<a href="drl_stats.php?firstat=40">Lowest all-time total fault score</a><br />
				<a href="drl_stats.php?firstat=41">Lowest all-time average fault score</a>
			</div>
		</div>
	</div>
</div>	
<div style="width:100%; float:none">
	<div class="head_3">Team statistics</div>
	<div style="text-align:center">Coming soon...</div>
	<div class="head_3">Competition statistics</div>
	<div style="text-align:center">Coming soon...</div>
</div>
</div>
</body>
</html>