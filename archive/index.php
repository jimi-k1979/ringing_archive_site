<!DOCTYPE html>
<?php
// early stages of mega-archive
/* let's work out what we want from this...
TEAM STATS
	team - deanery - years active
 all-time totals
  league stats
 	number of comps
	average ranking
	average position
	total faults
	average faults
	all time fd
	points
	average points
	total no results
  extant ladder stats
  	number of seasons
  	number of 'games'
	wins, loses, draws
	total faults for
	average faults for
	total faults against
	average faults against
	faults difference
	total points
	average points
  other results
  	number of comps
	average position
	total faults
	average faults
	total no results
  total stats (where applic)
  	number of comps
	average position
	total faults
	average faults
	total no results
	
 seasonal stats
  league stats
  	season
	no of competitions
	total faults
	average position
	ranking
	average faults
	no results
	fd
  ladder stats
  	number of 'games'
	wins, loses, draws
	total faults for
	average faults for
	total faults against
	average faults against
	faults difference
	total points
	average points
  other results stats
    season
	no of competitions
	total faults
	average position
	average faults
	no results
  
 collected results
  League }
  ladder  } all the same (ish)
  other  }
  	competition (include location)
	position (out of)/win, lose or draw [ladder only]
	faults
	points [league only]

COMPETITION STATS
 	Name - years active - league or other
 all time
   	no of events
	average entry
	total faults
	average faults
	average victory margin
 seasonal
   	season
	location
	no of teams
	total faults
	average faults
	winning team
	victory margin
	link to results

*/

/*
ladder averages query
SELECT team, COUNT( faults ) AS comps, SUM( faults ) AS tot_faults, AVG( faults ) AS ave_faults
FROM ladder_results
GROUP BY team

other comps averages query
SELECT team, COUNT(faults) AS comps, SUM(faults) AS tot_faults, AVG(faults) AS ave_faults FROM other_results GROUP BY team
*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
<p>This is eventually going to be the meag-archive</p>
</body>
</html>
