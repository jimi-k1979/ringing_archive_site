<?php
	include("../allpages/dbinfo.inc");
    include("../allpages/variables.php");
	include("drl_stat_queries.php");
	
	$cxn = mysqli_connect($host, $user, $passwd, $database);
	if(date("m")<4) {
		$year_start=$latest_year-1;
	} else {
		$year_start=$latest_year;
	}
	if(date("m")<11) {
		$year_end=$latest_year-1;
	} else {
		$year_end=$latest_year;
	}
	
	$first_year = $earliest_year;
	
    // fetch the number of no results for this year
	$query = "SELECT year, COUNT(team) AS DQs FROM DRL_no_results WHERE year = $year_start GROUP BY year ORDER BY year";
	$result = mysqli_query($cxn, $query);
	$dq_rows = mysqli_num_rows($result);
	if($dq_rows==0) {
		$num_dqs['year'] = $year_start;
		$num_dqs['DQs'] = 0; 
		$dq_team_rows = 0;
	} else {
		$num_dqs = mysqli_fetch_assoc($result); // gets the number of disqualifications for each year
	
		// get all the details
		$query = "SELECT * FROM DRL_no_results WHERE year = $year_start";
		$result = mysqli_query($cxn, $query);
		$dq_team_rows = mysqli_num_rows($result);
		for($i=0; $i<$dq_team_rows; $i++) {
			$row = mysqli_fetch_assoc($result); // get the disqualified teams
			$dq_team[$i]['team'] = $row['team'];
			$dq_team[$i]['comp'] = loc_check($row['competition'], $row['location'], $row['year']);
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - DRL Records</title>
<script type="text/javascript">
var year_end = <?php echo $year_end; ?>;
var year_start = <?php echo $year_start; ?>;
var first_year = <?php echo $first_year; ?>;
</script>
<script src="/allpages/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="/allpages/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<script src="stat_scripts.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/new2.css">
<link type="text/css" rel="stylesheet" href="/allpages/jquery-ui-1.8.6.custom.css">


<script type="text/javascript">
function drop_down_year_set (decade, selector) {
	// when more years are added to the database these two variables need to change
	var start_year = <?php echo $first_year; ?>;
	var end_year = <?php echo $year_start; ?>;
	var i
		
	$(selector).html('<option value="">Select a year</option>\n');
	
	if(decade<start_year) {
		for(i=start_year; i<decade+10; i++) {
			$(selector).append('<option values="'+i+'">'+i+'</option>\n');
		}
	} else if(end_year<decade+10) {
		for(i=decade; i<=end_year; i++) {
			$(selector).append('<option values="'+i+'">'+i+'</option>\n');
		}
	} else {
		for(i=decade; i<decade+10; i++) {
			$(selector).append('<option values="'+i+'">'+i+'</option>\n');
		}
	}
}


$(document).ready(function() {
// general display methods
	$('#tabs').tabs({
//		selected:1
	});
	
	accordions();
	searches();
	form_control();
	queries();
	
});
</script>
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

<!-- Sub menu Banner -->
<div id="sub_menu_banner">
<div id="sub_menu_container" style="width:520px;">
<div id="sub_unselected"><a class="sub_unselect" href="index.php">Home and Current Table</a></div>
<div id="sub_split"></div>
<?php
	echo "<div id='sub_selected'><a class='sub_unselect' href='drl_tables.php?season=".$year_start."'>Full Table</a></div>";
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_select" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<div style="background:white; padding-bottom:2px">
<h2>DRL records and statistics</h2>
<p>On there pages I have compiled some assorted records and statistics using the league tables and the results of the competitions which make up the tables. This page mostly has either top ten or ten most recent of a record, but clicking to the link at the bottom of each table should take you to the top 100 (or in some cases top 25) for each record. Links within the tables take you to the appropriate page of the archive for more details. If there are any ideas or requests for further statistics, please email them to me at <a href="mailto:webmaster@myconid.co.uk">the usual address</a> and I'll try to ask the database the right question to get the sensible answer...<br>
Throughout these pages the abbreviations NAIT Deaneries has been used for the Newton Abbot, Ipplepen and Torbay Deaneries, P&amp;I Deaneries for the Plymouth and Ivybridge Deaneries, and SRCY for The Society of Royal Cumberland Youths.</p>
<div id="tabs" style="margin:5px;">
	<ul>
    	<li><a href="#league">League records</a></li>
        <li><a href="#comp">Competition records</a></li>
        <li><a href="#team">Team records</a></li>
        <li><a href="#ringer">Ringer records</a></li>
		<li><a href="#search">Stat search</a></li>
    </ul>

    <div id="league">
	    <p>The records here are the league records, mainly using single season results. Many of the records are split into two categories: Teams which have rung in five or more competitions in a season and those for all teams which have participated in at lest one competition in a given season (the all-comers records). All time records are since <?php echo $first_year; ?>, and records set in <?php echo $year_end; ?> are marked with <span class="bold">bold</span> text.</p>
		<div id="league_accord">
			<h3>League Winners</h3>
            <div>
            <table>
                <tr>
                    <th>Year</th>
                    <th>5 or more competitions</th>
                    <th>All-comers</th>
                </tr>
                <tr>
                    <td class="center bold">2013</td>
                    <td class="center bold">Plymouth, Eggbuckland</td>
                    <td class="center bold">Plymouth, Eggbuckland</td>
                </tr>
                <tr>
                    <td class="center bold">2012</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                    <td class="center">SRCY</td>
                </tr>
                <tr>
                    <td class="center bold">2011</td>
                    <td class="center">Kingsteignton</td>
                    <td class="center">Kingsteignton</td>
                </tr>
                <tr>
                    <td class="center bold">2010</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                    <td class="center">Georgeham</td>
                </tr>
                <tr>
                    <td class="center bold">2009</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                    <td class="center">Devon Belles</td>
                </tr>
                <tr>
                    <td class="center bold">2008</td>
                    <td class="center">Dunsford</td>
                    <td class="center">Devon Belles</td>
                </tr>
                <tr>
                    <td class="center bold">2007</td>
                    <td class="center">Morthoe</td>
                    <td class="center">Winsford</td>
                </tr>
                <tr>
                    <td class="center bold">2006</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                    <td class="center">Ilfracombe</td>
                </tr>
                <tr>
                    <td class="center bold">2005</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                </tr>
                <tr>
                    <td class="center bold">2004</td>
                    <td class="center">Plymouth, Eggbuckland</td>
                    <td class="center">Devon Belles</td>
                </tr>
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=1">Click here for the full list</a></p>
            </div>
			<h3>Multiple League Winners</h3>
			<div>
			<p class="small center">Since 1986 only</p>
            <table>
            	<tr>
	            	<th style="width:300px">5 or more competitions</th>
    	    	    <th style="width:300px">All-comers</th>
        	    </tr>
                <tr>
                    <td class="center"><span class="bold">Plymouth, Eggbuckland: 16</span><br>1986, 1989-90, 1992-95, 1998, 2001, 2003-06, 2009-10, 2012</td>
                    <td class="center"><span class="bold">Devon Belles: 4</span><br>1986, 2004, 2008-09</td>
                </tr>
                <tr>
                    <td class="center"><span class="bold">Down St Mary: 3</span><br>1987-88, 1991</td>
                    <td class="center"><span class="bold">Ashreigney: 2</span><br>1991, 1998</td>
                </tr>
                <tr>
                    <td class="center"><span class="bold">Morthoe: 2</span><br>1996, 2007</td>
                    <td class="center"><span class="bold">Plymouth, Eggbuckland: 2</span><br>1993, 2005</td>
                </tr>
                <tr>
                    <td class="center">&nbsp;<br></td>
                    <td class="center"><span class="bold">Poughill (Cornwall): 2</span><br>1995-96</td>
                </tr>
			</table>
			</div>
        	<h3 id="stat03">Number of competitions in each season</h3>
            <div>
            <table id="stat3tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=3">Click here to see the full list</a></p>
            </div>
	        <h3 id="stat04">Number of teams entering each season</h3>
            <div>
	        <table id="stat4tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=4">Click here to see the full list</a></p>
            </div>
    	    <h3 id="stat05">Highest end of season ranking - 5 or more competitions</h3>
            <div>
			<table id="stat5tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=5">Click here to see the top 100 teams</a></p>
            </div>
        	<h3 id="stat06">Highest end of season ranking - All-comers</h3>
            <div>
			<table id="stat6tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=6">Click here to see the top 100 teams</a></p>
            </div>
	        <h3 id="stat07">Largest league victory margins</h3>
            <div>
				<p class="small center">Since 1986 only</p>
                <h4>5 or more competitions:</h4>
                <p class="center"><span class="bold">4.27</span> in 1990 <span class="italic">Plymouth, Eggbuckland (20.67) beat South Brent (16.40)</span></p>
                <p class="center"><a href="drl_stats_2.php?stat=7">Click here to see the full list for five or more competitions</a></p>
                <h4>All-comers:</h4>
                <p class="center"><span class="bold">10.00</span> in 1996 <span class="italic">Poughill (Cornwall) (32.00) beat Burrington (22.00)</span></p>
				<p class="center"><a href="drl_stats_2.php?stat=39">Click here to see the full list for all comers</a></p>
            </div>
    	    <h3 id="stat39">Smallest league victory margins</h3>
            <div>
				<p class="small center">Since 1986 only</p>
                <h4>5 or more competitions:</h4>
                <p class="center"><span class="bold">0.13</span> in 1992 <span class="italic">Plymouth, Eggbuckland (24.00) beat Molland (23.88)</span></p>
				<p class="center"><a href="drl_stats_2.php?stat=7">Click here to see the full list for five or more competitions</a></p>
                <h4>All-comers:</h4>
                <p class="center"><span class="bold">0.00</span> in 1987 <span class="italic">West Down (24.00, fault difference: 591.5) beat Exminster B (24.00, fault difference: 437.01)</span><br>and in 2009 <span class="italic">Devon Belles (24.00, fault difference: 309.00) beat Georgeham (24.00, fault difference: 278.25)</span></p>
                <p class="center"><a href="drl_stats_2.php?stat=39">Click here to see the full list for all comers</a></p>
            </div>
        	<h3 id="stat08">Highest ranking point total</h3>
            <div>
			<table id="stat8tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=8">Click here to see the top 100 teams</a></p>
            </div>
	        <h3 id="stat09">Best end of season fault difference</h3>
            <div>
			<table id="stat9tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=9">Click here to see the top 100 teams</a></p>
            </div>
        </div>
    </div>

    <div id="comp">
    	<p>The records here are the for single events held since <?php echo $first_year ?>. Only competitions that were used in the calculation of the league tables are considered in the records. <span class="bold">Bold</span> type indicates a record set in <?php echo $year_start; ?>.</p>
        <div id="comp_accord">
        	<h3 id="stat10">Highest competition entry</h3>
            <div>
			<table id="stat10tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=10">Click here to see the top 100 competitions</a></p>
            </div>
            <h3 id="stat11">Lowest total fault score</h3>
            <div>
			<table id="stat11tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=11">Click here to see the top 100 competitions</a></p>
            </div>
            <h3 id="stat12">Lowest average fault score</h3>
            <div>		
			<table id="stat12tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=12">Click here to see the top 100 competitions</a></p>
            </div>
            <h3 id="stat13">Highest total fault score</h3>
            <div>
			<table id="stat13tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=13">Click here to see the top 100 competitions</a></p>
            </div>
            <h3 id="stat14">Highest average fault score</h3>
            <div>
			<table id="stat14tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=14">Click here to see the top 100 competitionss</a></p>
            </div>
            <h3 id="stat15">Largest competition victory margin</h3>
            <div>
			<table id="stat15tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=15">Click here to see the top 100 competitions</a></p>
			</div>
            <h3 id="stat16">Smallest competition victory margin</h3>        	
            <div>
			<table id="stat16tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=16">Click here to see the top 100 competitions</a></p>
			</div>
        </div>
    </div>
 	    
    <div id="team">
	    <p>The records in this section are for individual teams. The all-time records include all seasons since <?php echo $first_year; ?>. <span class="bold">Bold</span> type indicates a record set in <?php echo $year_start; ?>.</p>
        <div id="team_accord">
        	<h3 id="stat17">Most competitions in a season</h3>
            <div>
			<table id="stat17tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=17">Click here to see the top 100 teams</a></p>
            </div>
            <h3 id="stat18">All-time most competitions</h3>
            <div>
			<table id="stat18tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=18">Click here to see the top 100 teams</a></p>
            </div>
	
			<h3 id="stat41">Most competition wins in a season</h3>
            <div>
			<table id="stat41tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=41">Click here to see the top 100 teams</a></p>
            </div>
			<h3 id="stat42">All-time most competition wins</h3>
            <div>
			<table id="stat42tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=42">Click here to see the complete list</a></p>
            </div>
			<h3 id="stat43">Best win percentage in a season</h3>
            <div>
			<p class="small center">Number of wins divided by number of competitions
				<br>Five or more competitions in a year</p>
			<table id="stat43tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=43">Click here to see the top 100 teams</a></p>
            </div>
			<h3 id="stat44">All-time best win percentage</h3>
            <div>
			<p class="small center">Number of wins divided by number of competitions
				<br>20 or more competitions</p>
			<table id="stat44tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=44">Click here to see the complete list</a></p>
            </div>
			
            <h3 id="stat19">Lowest single fault score</h3>
            <div>
			<table id="stat19tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=19">Click here to see the top 100 teams</a></p>
            </div>
            <h3 id="stat20">Highest single fault score</h3>
            <div>
			<table id="stat20tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=20">Click here to see the top 100 teams</a></p>
			</div>
			<h3 id="stat21">Lowest annual average fault score</h3>
			<div>
			<p class="small center">Five or more competitions in a year</p>
			<table id="stat21tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=21">Click here to see the top 100 teams</a></p>
			</div>
			<h3 id="stat22">Highest annual average fault score</h3>
			<div>
			<p class="small center">Five or more competitions in a year</p>
			<table id="stat22tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=22">Click here to see the top 100 teams</a></p>
			</div>
			<h3 id="stat23">Lowest all-time total fault score</h3>
            <div>
            <p class="small center">20 or more competitions</p>
			<table id="stat23tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=23">Click here to see the top 25 teams</a></p>
            </div>
            <h3 id="stat24">Highest all-time total fault score</h3>
            <div>
            <p class="small center">20 or more competitions</p>
			<table id="stat24tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=24">Click here to see the top 25 teams</a></p>
            </div>
			<h3 id="stat25">Lowest all-time average fault score</h3>
			<div>
			<p class="small center">20 or more competitions</p>
			<table id="stat25tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=25">Click here to see the top 25 teams</a></p>
			</div>
			<h3 id="stat26">Highest all-time average fault score</h3>
            <div>
			<p class="small center">20 or more competitions</p>
			<table id="stat26tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=26">Click here to see the top 25 teams</a></p>
            </div>
            <h3 id="stat27">Highest fault score to win a competition</h3>
            <div>
			<table id="stat27tab">
            </table>
			<p class="center"><a href="drl_stats_2.php?stat=27">Click here to see the top 100 teams</a></p>
			</div>
            <h3 id="stat28">Lowest fault score to come last in a competition</h3>
            <div>
			<p class="small center">Excludes any 'no results'</p>
			<table id="stat28tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=28">Click here to see the top 100 teams</a></p>
            </div>
            <h3 id="stat29">Largest all-time total fault difference</h3>
            <div>
			<table id="stat29tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=29">Click here to see the top 100 teams</a></p>
            </div>
            <h3 id="stat30">Disqualifications and No Results</h3>
            <div>
<?php		echo "<h4>$num_dqs[year]: $num_dqs[DQs]</h4>"; ?>
			<p style="width:500px;margin: 0 auto">
<?php			for($i=0; $i<$dq_team_rows; $i++) {
					if($i==$dq_team_rows-1) echo "<span class=\"bold\">".$dq_team[$i]['team'].":</span> ".$dq_team[$i]['comp'];
					else echo "<span class=\"bold\">".$dq_team[$i]['team'].":</span> ".$dq_team[$i]['comp'].", ";					
				}
?>
			</p>
			<p class="center"><a href="drl_stats_2.php?stat=30">Click here to see the full list of teams</a></p>
	        </div>
        </div>
    </div>
        
    <div id="ringer">
    	<p>These records are only concerned with the ringers who rang for the team that won the compeititons in question. Also, since I don't have anything like a complete list of the ringers for every competition so it's not as big an archive as the results archive (as yet), sorry! If you can help, please get in contact.</p>
		<div id="ringer_accord">
			<h3 id="stat31">Most competition wins</h3>
			<div>
			<table id="stat31tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=31">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat32">Most final wins</h3>
			<div>
			<p class="center small">Winners of either the 8 bell or Major finals</p>
			<table id="stat32tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=32">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat33">Most 8 bell competition wins</h3>
			<div>
			<table id="stat33tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=33">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat34">Most 6 bell competition wins</h3>
			<div>
			<p class="center small">includes North and South Devon Qualifiers, Devon Minor Final and Major Final</p>
			<table id="stat34tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=34">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat35">Most Major Final wins</h3>
			<div>
			<table id="stat35tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=35">Click here to see the top 25 ringers</a></p>

			</div>
			<h3 id="stat36">Most Minor Final wins</h3>
			<div>
			<table id="stat36tab">
			</table>
			<p><a href="drl_stats_2.php?stat=40">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat37">Most qualifier wins</h3>
			<div>
			<p class="center small">includes both North and South Devon Qualifiers</p>
			<table id="stat37tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=36">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat38">Most wins on treble</h3>
			<div>
			<table id="stat38tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=37">Click here to see the top 25 ringers</a></p>
			</div>
			<h3 id="stat40">Most wins on tenor</h3>
			<div>
			<table id="stat40tab">
			</table>
			<p class="center"><a href="drl_stats_2.php?stat=38">Click here to see the top 25 ringers</a></p>
			</div>
        </div>
    </div>
	<div id="search">
	<p>This tab gives you the stat searches - at the moment its still a bit of a contruction site, but I'm working on it. The results from your searches will appear below the accordion.</p>
		<div id="search_accord">
			<h3>Team search</h3>
			<div>
			<p>Using the form below you can select to view the statistics and/or results for every season from <?php echo $first_year; ?> for any tower that has entered a competition since then. The towers are ordered as they would be in 'Dove' so, for example, you'll find Eggbuckland under 'P' for Plymouth and Alphington under 'E' for Exeter. Bear in mind that some of the teams have entered a lot of competitions and it may tack some time to retrieve the data from the database if you select all the options! Also some towers will not have any results (eg Exeter, St Davids B) as these teams were involved with the ringing ladder but have not eneterd any competitions proper.</p>
			<form method="get" action="drl_team.php" id="team_search_form" name="team_details" style="text-align:center">
				<div class="center">
					<select name="tg" id="tg">
						<option value="0">Select a group</option>
						<option value="1">A-E</option>
						<option value="2">F-J</option>
						<option value="3">K-O</option>
						<option value="4">P-T</option>
						<option value="5">U-Z</option>
					</select>
					<select name="team" id="team_menu">
						<option value="">Select a letter group first</option>
					</select>
				</div>
				<div class="center">
					<label>
						<input id="res0" name="res" type="radio" value="0"> Statistics only
					</label>
					<label>
						<input id="res1" name="res" type="radio" value="1"> Results only
					</label>
					<label>
						<input id="res2" name="res" type="radio" value="2" checked="checked"> Both statistics and results
					</label>
				</div>
				<div class="center">
					<label>
						<input id="range0" name="range" type="radio" value="0" checked="checked"> All-time
					</label>
					<label>
						<input id="range1" name="range" type="radio" value="1"> Year range -
					</label> 
					<label>
						Start year: <input id="start" name="start" type="text" style="width:50px" maxlength="4" disabled="true">
					</label>
					<label>
						&nbsp;End year: <input id="end" name="end" type="text" style="width:50px" maxlength="4" disabled="true"> 
					</label>
				</div>
	
				<div class="center">
					<fieldset style="width:432px; margin:0 auto">
					<legend><a href="#" id="toggle">Advanced options (click to toggle)</a></legend>
					<div id="stat_options" style="display:none">
						<p class="center">Select which stats you want to display in the seasonal stats by using the options below<br><a href="#" id="clear">Clear all</a> | <a href="#" id="selectall">Select all</a></p>
						<div style="width:140px; margin:2px; float:left; text-align:left">
							<label>
								<input id="adv0" name="advanced" type="checkbox" checked="checked"> Competitions
							</label><br>
							<label>
								<input id="adv1" name="advanced" type="checkbox" checked="checked"> Total faults
							</label><br>
							<label>
								<input id="adv2" name="advanced" type="checkbox" checked="checked"> Average position
							</label>
						</div>
						<div style="width:140px; margin:2px; float:left; text-align:left">
							<label>
								<input id="adv3" name="advanced" type="checkbox" checked="checked"> Ranking
							</label><br>
							<label>
								<input id="adv4" name="advanced" type="checkbox" checked="checked"> Average faults
							</label><br>
							<label>
								<input id="adv5" name="advanced" type="checkbox" checked="checked"> No Results
							</label>
						</div>
						<div style='width:140px; margin:2px; float:left; text-align:left'>
							<label>
								<input id="adv6" name="advanced" type="checkbox"  checked="checked"> Total points
							</label>
							<label><br>
								<input id="adv7" name="advanced" type="checkbox" checked="checked"> Fault difference
							</label>
						</div>
					</div>
					</fieldset>
				</div>
				<div class="center">
					<input type="button" value="Go!" id="team_search">
				</div>
			</form>
			</div>
			<h3>Competition search</h3>
			<div>
				<p>The drop down menu below gives access to the statistics for each competition. The competition page gives the all-time stats and then the individual event averages, plus a link to each individual event's results.</p>
				<form method="post" action="drl_comps.php" id="comp_details" name="comp_details">
					<div class="center">
<?php
	$query="SELECT * FROM DRL_competitions";
	$result=mysqli_query($cxn,$query);
	echo "<select name=\"comp_search\" id=\"comp_search\">\n";
	echo "<option value=\"\">Select a competition</option>\n";
	while($row = mysqli_fetch_assoc($result)) { 
		echo("<option value=\"$row[competition]\">$row[competition]</option>\n");
	}
	echo("</select>\n");
?>
						<input type="button" id="comp_search_go" name="comp_search_go" value="Go!">
					</div>
				</form>
			</div>
			<!--h3>Ringer search</h3>
			<div>
			<p>I'll sort this out when i get time...</p>
			</div-->
		</div>
		<div id="search_results_here">
		
		</div>
	</div>
</div>
</div>
</body>
</html>
