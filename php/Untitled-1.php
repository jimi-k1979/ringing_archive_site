<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>

<?php
echo "Hello World";

/* MySQL queries: 
   ++++++++++++++
	 
	 ladder tables :- 
SELECT lad_points_alloc.team, COUNT(lad_points_alloc.points) AS played, SUM(lad_points_alloc.points) AS points
FROM lad_points_alloc INNER JOIN ladder_fixtures ON lad_points_alloc.fixture_id = ladder_fixtures.fixture_id
WHERE `ladder_fixtures`.`group`=1 AND ladder_fixtures.section='b' AND ladder_fixtures.season='2009a'
GROUP BY lad_points_alloc.team 
ORDER BY SUM(lad_points_alloc.points) DESC;

Altering the WHERE clause will alter the table displayed.
   
	 ladder results :-
SELECT win_team, win_faults, lose_team, lose_faults
FROM lad_res_classified
WHERE season='2009a' AND section='a' AND lad_group=1;

Altering the WHERE clause will alter the results displayed
*/

?>

</body>
</html>
