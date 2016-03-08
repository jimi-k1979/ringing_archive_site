<?php
// competition class - this needs to know whether it is DRL or not.
include_once 'database.php';

class Competition {
	private $competition;
	private $DRL_flag; // boolean, true is a DRL compeititon
	
	// setter and getters
	function setCompetition($name) {
		// sanitise input
		$string = trim($name);
		$string = stripslashes($string);
		$string = htmlspecialchars($string);
		
		$this->competition = $string;
	
	}
	function getCompetition() {
		return $this->competition;
	}
	
	function setDRL_flag($bool) {
		// check we have a boolean
		if(!is_bool($bool)) {
			throw new InvalidArgumentException('DRL flag is not a boolean');
		}
		$this->DRL_flag = $bool;
	}
	function getDRL_flag() {
		return $this->DRL_flag;
	}
	
	function DRL_flagFromDB() {
		// check there is a competition name
		if($this->competition=="") {
			throw new Exception('No competition name');
		}
		// open a DB connection
		try {
			$cxn = new PDO(DB::dsn, DB::user, DB::password);
		} catch (PDOException $e) {
			$this->DRL_flag = false;
		}
		
		// assume compeititon isn't escaped
		$safe = $cxn->quote($this->competition);
		$safe = strtr($safe, array('_' => '\_', '%' => '\%'));
		
		// is the competition in the DRL_competition table?
		$query = "SELECT COUNT(*) FROM DRL_competitions WHERE competition = $safe";
		if($result = $cxn->query($query)) {
			if ($result->fetchColumn(0) != 0) { // it's there! make the flag true
				$this->DRL_flag = true;
			} else { // it's not a drl competition or new
				$this->DRL_flag = false;
			}
		} else {
			$this->DRL_flag = false;
		}
		$cxn = null;
	}
	
	function __construct() {
		if(func_num_args()==2) { // competition first then flag second
			$this->setCompetition(func_get_arg(0));
			$this->setDRL_flag(func_get_arg(1));
		} elseif(func_num_args()==1) { // competition only
			$this->setCompetition(func_get_arg(0));
			$this->DRL_flagFromDB();
		} else {
			$this->competition="";
			$this->DRL_flag=false;
		}
	}
	
}
?>