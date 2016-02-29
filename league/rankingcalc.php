<?php
	// website header files 
	include("../allpages/dbinfo.inc");
?>
<!DOCTYPE html>
<html>
<head>
<title>James Kerslake's Devon-style bellringing site - DRL: How it works</title>
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
	echo "<div id=\"sub_unselected\"><a class=\"sub_unselect\" href=\"drl_tables.php?season=".($latest_year-1)."\">Full Table</a></div>";
} else {
	echo "<div id=\"sub_unselected\"><a class=\"sub_unselect\" href=\"drl_tables.php?season=".$latest_year."\">Full Table</a></div>";
}
?>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_archive2.php">Archive</a></div>
<div id="sub_split"></div>
<div id="sub_unselected"><a class="sub_unselect" href="drl_stat_home2.php">Statistics</a></div>
<div id="sub_split"></div>
<div id="sub_selected"><a class="sub_select" href="rankingcalc.php">How it Works</a></div>
</div>
</div>

<!-- content -->
<div id="main_content">
  <h2>Devon Ringing League: How it Works</h2>
  
  <p>The system used in the DRL, introduced at the end of the 2005 season, not only takes into account both the finishing position and faults scored by the teams, but also accounts for the number of teams entering a competition. This gives a truer reflection of the teams relative strengths as it is 'harder' to win a 15 team competition than one with 5 entries. This feature allows the table to include six and eight bell competitions as it is the relative finishing positions and not the number of faults scored by a team the is most important.</p>
	
  <p>The system works by simply awarding each team a number of ranking points which is dependent on the number of teams in a competition and the given team's finishing position. These ranking points are totalled and then divided by the number of competitions that team has entered to get that teams 'Ranking'. Two ranking points are awarded for each team beaten in a competition, and one point is awarded for a tie. The teams are ordered by ranking, highest to lowest, and any ties in ranking are sorted by 'fault difference'.</p>
  	
  <p>Fault difference is not unlike goal difference in football or points difference in rugby, but is calculated in a slightly different manner due to the nature of ringing competitions. As the aim is to achieve as low a score as possible, any faults given to a team can be considered as faults against that team, and therefore the faults scored by the other teams are faults for that team. Hence, for a given competition, the number of faults scored by a team subtracted from the total number of faults scored by all teams in that competition gives the faults for, and the faults against are calculated by multiplying a team's score by the number of other teams in the competition. The fault difference is then simply the faults for less the faults against. This method means that large positive fault differences are better than small ones which are, in turn, better than negative ones.</p>
  
  <p>Disqualifications and other 'no results' are taken into account where the information is available. Teams that are disqualified are treated as if they finished last, i.e. get no ranking points and have the competitions entered increased by one. If there are multiple teams disqualified they are all deemed to have finished equal last and all get no ranking points. The team that finished with the highest number of faults are assumed to have 'beaten' all disqualified teams and are given the corresponding number of ranking points</p>
	
  <h3>Ranking calculation example</h3>
  
  <p>The following examples uses results from the 2005 season.</p>
  
  <p>At the Spreyton competition, in which there were 10 entries, the team that finished first (South Tawton) are awarded 18 ranking points (2 &times; 9 beaten teams), the second place team (Exminster) receive 16 points and so on. There was a tie for 8th place (Sampford Courtenay A and Ide), and these teams are both awarded 3 ranking points (2 &times; 1 beaten team + 1 for the tie). The ranking points awarded for the competition are given below:</p>
    
	<table>
		<tr>
			<th>Team</th>
			<th>Points</th>
		</tr>
		<tr><td>South Tawton</td><td>18</td></tr>
		<tr><td>Exminster</td><td>16</td></tr>
		<tr><td>Chagford</td><td>14</td></tr>
		<tr><td>Down St Mary</td><td>12</td></tr>
		<tr><td>Lapford</td><td>10</td></tr>
		<tr><td>Mariansleigh</td><td>8</td></tr>
		<tr><td>Colebrooke</td><td>6</td></tr>
		<tr><td>Ide</td><td>3</td></tr>
		<tr><td>Sampford Courtenay A</td><td>3</td></tr>
		<tr><td>Sampford Courtenay B</td><td>0</td></tr>
	</table>
	  
  <p>At the Widecombe in the Moor competition there were twelve entries 
    and the points for this copmetition are given below:</p>

	<table>
		<tr>
			<th>Team</th>
			<th>Points</th>
		</tr>
		<tr><td>Plymouth, Eggbuckland</td><td>22</td></tr>
		<tr><td>Lamerton</td><td>20</td></tr>
		<tr><td>Exeter, St Petrock</td><td>18</td></tr>
		<tr><td>South Tawton</td><td>16</td></tr>
		<tr><td>South Brent</td><td>14</td></tr>
		<tr><td>Dunsford</td><td>12</td></tr>
		<tr><td>Buckland in the Moor</td><td>10</td></tr>
		<tr><td>Stoke Gabriel</td><td>7</td></tr>
		<tr><td>Chagford</td><td>7</td></tr>
		<tr><td>Burrington</td><td>4</td></tr>
		<tr><td>West Alvington</td><td>2</td></tr>
		<tr><td>Collaton St Mary</td><td>0</td></tr>
	</table>
   
  <p>The rankings from these two competitions are calculated by adding up the total points scored by each team and the dividing by the number of competitions entered. South Tawton's ranking, for example, is therefore (18 + 16) / 2 = 17 so the table for these two competitions is:</p>
	
	<table>
		<tr>
			<th>Team</th>
			<th>Rank.</th>
		</tr>
		<tr><td>Plymouth, Eggbuckland</td><td>22</td></tr>
        <tr><td>Lamerton</td><td>20</td></tr>
        <tr><td>Exeter, St Petrock</td><td>18</td></tr>
        <tr><td>South Tawton</td><td>17</td></tr>
        <tr><td>Exminster</td><td>16</td></tr>
        <tr><td>South Brent</td><td>14</td></tr>
        <tr><td>Down St Mary</td><td>12</td></tr>
        <tr><td>Dunsford</td><td>12</td></tr>
        <tr><td>Chagford</td><td>10.5</td></tr>
        <tr><td>Buckland-in-the-Moor</td><td>10</td></tr>
        <tr><td>Lapford</td><td>10</td></tr>
        <tr><td>Mariansleigh</td><td>8</td></tr>
        <tr><td>Stoke Gabriel</td><td>7</td></tr>
        <tr><td>Colebrooke</td><td>6</td></tr>
        <tr><td>Burrington</td><td>4</td></tr>
        <tr><td>Ide</td><td>3</td></tr>
        <tr><td>Sampford Courtenay A</td><td>3</td></tr>
        <tr><td>West Alvington</td><td>2</td></tr>
        <tr><td>Collaton St Mary</td><td>0</td></tr>
        <tr><td>Sampford Courtenay B</td><td>0</td></tr>
	</table>
  
  <p>In order to correctly position teams such as Down St Mary and Dunsford who have the same ranking their faults difference must be calculated. In the Spreyton competition there was a total of 630.5 faults scored, of which Down St Mary scored 53. Their faults for is 630.5 - 53 = 577.5 and their faults against is 53 &times; 9 = 477, so Down St Mary's fault difference is 577.5 - 477 = 100.5. Using the same method, Dunsford's fault difference is (676 - 52) - (52 &times; 11)  = 52. An easier, and equivalent, way of working out the fault difference is to multiply the number of teams by the fault score of the individual team and then subtract this from the total fault score for the competition. For Dunsford, in this example, we can re-write the calculation like this: 676 - (52 &times; 12) = 52. Calculating the other fault differences in the same way the table becomes:</p>
	
	<table>
		<tr>
			<th>Team</th>
			<th>Rank</th>
			<th>Fault Diff</th>
		</tr>
		<tr><td>Plymouth, Eggbuckland</td><td>22</td><td>538</td></tr>
        <tr><td>Lamerton</td><td>20</td><td>220</td></tr>
        <tr><td>Exeter, St Petrock</td><td>18</td><td>118</td></tr>
        <tr><td>South Tawton</td><td>17</td><td>330.5</td></tr>
        <tr><td>Exminster</td><td>16</td><td>220.5</td></tr>
        <tr><td>South Brent</td><td>14</td><td>76</td></tr>
        <tr><td>Down St Mary</td><td>12</td><td>100.5</td></tr>
        <tr><td>Dunsford</td><td>12</td><td>52</td></tr>
        <tr><td>Chagford</td><td>10.5</td><td>207.5</td></tr>
	    <tr><td>Lapford</td><td>10</td><td>95.5</td></tr>
        <tr><td>Buckland-in-the-Moor</td><td>10</td><td>34</td></tr>
        <tr><td>Mariansleigh</td><td>8</td><td>-4.5</td></tr>
        <tr><td>Stoke Gabriel</td><td>8</td><td>22</td></tr>
        <tr><td>Colebrooke</td><td>6</td><td>-49.5</td></tr>
        <tr><td>Burrington</td><td>4</td><td>4</td></tr>
        <tr><td>Ide</td><td>3</td><td>-204.5</td></tr>
        <tr><td>Sampford Courtenay A</td><td>3</td><td>-204.5</td></tr>
        <tr><td>West Alvington</td><td>2</td><td>-2</td></tr>
        <tr><td>Collaton St Mary</td><td>0</td><td>-176</td></tr>
        <tr><td>Sampford Courtenay B</td><td>0</td><td>-369.5</td></tr>
	</table>
  
  <p>As Ide and Sampford Courtenay obtained their ranking in the same compeition their fault differences are the same and they are arranged alphabetically. Over the course of a season this type of result would disappear, unless neither team enters another competition or they enter all the same competitions and always tie.</p>
  
  <p>Over the course of a season a team's ranking fluctuates with their results, the table below shows this for an imaginary team. The columns are competition results, points, points running total and ranking:</p>
  
	<table>
		<tr><th>Result</th><th>Pts</th><th>Tot pts</th><th>Ranking</th></tr>
		<tr><td>2nd out of 7</td><td>10</td><td>10</td><td>10</td></tr>
        <tr><td>5th out of 6</td><td>2</td><td>12</td><td>6</td></tr>
        <tr><td>5th out of 12</td><td>14</td><td>26</td><td>8.67</td></tr>
        <tr><td>7th out of 12</td><td>10</td><td>36</td><td>9</td></tr>
        <tr><td>4th out of 12</td><td>16</td><td>52</td><td>10.4</td></tr>
        <tr><td>1st out of 4</td><td>6</td><td>58</td><td>9.67</td></tr>
        <tr><td>8th out of 11</td><td>6</td><td>64</td><td>9.14</td></tr>
        <tr><td>1st out of 9</td><td>16</td><td>80</td><td>10</td></tr>
        <tr><td>2nd out of 9</td><td>14</td><td>94</td><td>10.44</td></tr>
	</table>
</div>
</body>
</html>
