<?php
// event classes - doesn't need to know what sort of event it is, the competition knows!
include_once 'database.php';
include_once 'competition.php';
include_once 'result.php';

class Event {
	private $event_id;
	private $competition; // competition object - with DRL flag!
	private $year; // the year of the event
	private $location; // where the event was held
	private $results_list; // an array of result objects
	
	// getters and setters
	function setEvent_id($number) {
		// check this is an integer
		if(!(is_int($number) or ctype_digit($number))) {
			throw new InvalidArgumentException('Event ID is not a number');
		}
		$this->event_id = $number;
	}
	function getEvent_id() {
		return $this->event_id;
	}
	
	// takes a competition name as string and sets up an object
	function setCompetition($string) {
		// sanitise input
		$string = trim($string);
		$string = stripslashes($string);
		$this->competition->setCompetition($string);
		$this->competition->DRL_flagFromDB();
	}
	function getCompetition() {
		return $this->competition->getCompetition();
	}
	function getCompetitionFlag() {
		return $this->competition->getDRL_flag();
	}
	
	function setYear($string) {
		if(!ctype_digit($string)) {
			throw new InvalidArgumentException('Year is not numeric');
		}
		$this->year = $string;
	}
	function getYear() {
		return $this->year;
	}
	
	function setLocation($string) {
		$string = trim($string);
		$string = stripslashes($string);
		$this->location = $string;
	}
	function getLocation() {
		return $this->location;
	}
	
	// no setter for results -> initialised to an empty array and values appended
	function getResults() { // returns the array of result objects
		return $this->results_list;
	}
	function addToResults() {
		if(func_num_args()==4){ // position, faults, team, ranking points
			// if the competition is a DRL one we need to set up a drl result
			if($this->getCompetitionFlag()) {
				$this->results_list[] = new DRLResult(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			} else { // otherwise set up a non DRL list
				$this->results_list[] = new Result(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			}
		} elseif(func_num_args()==3){ // position, faults, team
			// if the competition is a DRL one we need to set up a drl result
			if($this->getCompetitionFlag()) {
				$this->results_list[] = new DRLResult(func_get_arg(0), func_get_arg(1), func_get_arg(2), 0);
			} else { // otherwise set up a non DRL list
				$this->results_list[] = new Result(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			}
		} else {
			// shouldn't get here
			throw new Exception('Not the right number of arguments in addToResults');
		}
	}
	function DBResults() { // get results from db
		// if it is a new event there will be no event_id set so will fail out here
		if(!isset($this->event_id) or $this->event_id == 0) {
			throw new Exception("DBResults: no event_id set");
		}
		$cxn = new PDO(DB::dsn, DB::user, DB::password);
		
		if($this->getCompetitionFlag()) { // DRL competition
			$query = "SELECT position, faults, team FROM DRL_results WHERE event_id = ".$this->event_id;
		} else { // non DRL competition
			$query = "SELECT position, faults, team FROM other_results WHERE event_id = ".$this->event_id;
		}
		// get the result set
		$result = $cxn->query($query);
		$result->execute();
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
		
		// add the results to the array
		foreach($row as $next) {
			$this->addToResults($next['position'], $next['faults'], $next['team']);
		}
		
		// sort the results by position
		$this->sortResults("position");

		try { // calculate the ranking points in DRL competitions
			$this->calculateRankingPoints();
		} catch (InvalidArgumentException $e) {
			if(strcmp($e->getMessage(),'non DRL event')) {
				break; // not a big deal, just carry on
			} else {
				throw new Exception('Big error in DBResults'); // hmmm.. shouldn't get here!
			}
		}
	}
	// calculate the ranking points for the event -> NEEDS A LIST OF DRLResults
	function calculateRankingPoints() {
		// check that this is a drl results list
		if(!method_exists($this->results_list[0], "getRankingPoints")) {
			throw new InvalidArgumentException ('non DRL event');
		}
		// sort the results
		$this->sortResults("position");
		
		// the number of points for 1st places is one less than the number of teams multiplied by two
		$points = (count($this->results_list)-1)*2;
		
		for($i=0; $i<count($this->results_list); $i++) {
			if($i == 0) { // first places gets the most points
				$this->results_list[$i]->setRankingPoints($points);
				$points -= 2;
			} elseif ($this->results_list[$i]->getFaults()<0.01) { // any team that has been disqualified (0 faults) gets no points
				$this->results_list[$i]->setRankingPoints(0);
			} else { // all other results
				if($this->results_list[$i]->getFaults() != $this->results_list[$i-1]->getFaults()) {
					$this->results_list[$i]->setRankingPoints($points);
				} else {
					$this->results_list[$i-1]->setRankingPoints($points+1);
					$this->results_list[$i]->setRankingPoints($points+1);
				}
				$points -= 2;
			}
		}
	}
	
	// constructor
	function __construct() {
		if(func_num_args()==3) { // competition, year, location
			$this->competition = new Competition();
			$this->setCompetition(func_get_arg(0));
			$this->setYear(func_get_arg(1));
			$this->setLocation(func_get_arg(2));
			$this->event_id = 0;
			$this->results_list = [];
		} else {
			$this->event_id = 0;
			$this->competition = new Competition();
			$this->year = "";
			$this->location = "";
			$this->results_list = [];
		}
	}
	
	// sorting the results array
	function sortResults($sort_by) {
		if(strcmp(strtolower($sort_by),"team")==0) {
			// order array by team name
			usort($this->results_list, array($this, "compareTeams"));
		} else { 
			// default to ordering by position (NB not the same as by faults)
			usort($this->results_list, array($this, "comparePositions"));
		}
	}
	function compareTeams($a, $b) {
		return strcmp($a->getTeam(), $b->getTeam());
	}
	function comparePositions($a, $b) {
		if ($a->getPosition() == $b->getPosition()) {
			return 0;
		} elseif ($a->getPosition() > $b->getPosition()) {
			return 1;
		} else {
			return -1;
		}
	}
	
}
?>