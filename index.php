<html>
<head>
<title>Test the archive</title>
</head>
<body>
<?php
echo "<pre>";
include 'event.php';

try {
	$event = new Event();
} catch (InvalidArgumentException $e) {
	echo "That's not right!\nLook: ".$e->getMessage()."\n";
	
}

echo DB::dbhost."\n";
$event->setCompetition("South Brent");

echo "competition: ".$event->getCompetition()."\tDRL flag: ";
if($event->getCompetitionFlag()) {
	echo "True\n";
} else {
	echo "False\n";
}
echo "Let's add some results:\n";

$event->setEvent_id(1);
$event->DBResults();
foreach ($event->getResults() as $result) {
	echo "team: ".$result->getTeam()." ".$result->getFaults()." ".$result->getRankingPoints()."\n";
}


echo "</pre>";
?>
</body>
</html>