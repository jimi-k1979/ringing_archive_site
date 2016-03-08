<?php
//result.php
// Devon Results Archive result object

// General result class for general use
class Result {
	protected $position;
	protected $faults;
	protected $team; // team name string
	
	// setters and getters
	function setPosition($number) {
		// position should always be an integer value
		if(!(is_int($number) or ctype_digit($number))) {
			throw new InvalidArgumentException('Position not an integer');
		}
		$this->position = $number;
	}
	function getPosition() {
		return $this->position;
	}
	
	function setFaults($number) {
		// faults should always be a float
		if(!is_numeric($number)) {
			throw new InvalidArgumentException('Faults not a number');
		}
		$this->faults = $number;
	}
	function getFaults() {
		return $this->faults;
	}
	
	function setTeam($string) {
		// sanitise input
		$string = trim($string);
		$string = stripslashes($string);
		$string = htmlspecialchars($string);
		$this->team = $string;
	}
	function getTeam() {
		return $this->team;
	}
	
	// constructor
	function __construct() {
		if(func_num_args()==3){// position, faults, team
			// initialise a full result
			$this->setPosition(func_get_arg(0));
			$this->setFaults(func_get_arg(1));
			$this->setTeam(func_get_arg(2));
		} else {
			// initialise an empty object
			$this->position = 0;
			$this->faults = 0.0;
			$this->team = "";
		}
	}
}

// separate child class for DRL archive results
class DRLResult extends Result {
	private $ranking_points;
	
	function setRankingPoints($number) {
		// ranking points are always integers
		if(!(is_int($number) or ctype_digit($number))) {
			throw new InvalidArgumentException('Ranking Points not an integer');
		}
		$this->ranking_points = $number;
	}
	function getRankingPoints() {
		return $this->ranking_points;
	}
	
	function __construct() {
		if(func_num_args()==4) { // position, faults, team, ranking points
			// initialise a full result
			$this->setPosition(func_get_arg(0));
			$this->setFaults(func_get_arg(1));
			$this->setTeam(func_get_arg(2));
			$this->setRankingPoints(func_get_arg(3));
		} elseif (func_num_args()==3) { // position, faults, team
			$this->setPosition(func_get_arg(0));
			$this->setFaults(func_get_arg(1));
			$this->setTeam(func_get_arg(2));
		} else {
			// initialise an empty object
			parent::__construct();
			$this->ranking_points = 0;
		}
	}
}

?>