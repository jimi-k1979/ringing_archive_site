<?php
// team.php
// Devon Results Archive team object
// requires the database variables to work
include_once 'database.php';

class Team {
	private $name; // this the primary kay
	private $deanery;
	// not using first year as it is not normally needed
	
	// setters and getters
	function setName($string) {
		// sanitise input
		$string = trim($string);
		$string = stripslashes($string);
		$this->name = $string;
	}
	function getName() {
		return $this->name;
	}
	
	function setDeanery($string) {
		// sanitise input
		$string = stripslashes($string);
		$string = trim($string);
		
		// check the deanery is in the database
		try {
			$cxn = new PDO(DB::dsn, DB::user, DB::password);
			
			// escape the string for the DB
			$safe = $cxn->quote($string);
			$safe = strtr($safe, array('_' => '\_', '%' => '\%'));
			
			// is the deanery in the data
			$query = "SELECT count(*) FROM deaneries WHERE deanery = $safe";
			if($res = $cxn->query($query)) {
				if($res->fetchColumn()==0) { // no
					throw new Exception ('No deanery in the database');
				}
				
			}
		} catch (PDOException $e) {
			throw new InvalidArgumentException ('DB connection error ('.$cxn->connect_errno.') '.$cxn->connect_error.'\n');
		} catch (Exception $e) { // treat both errors as an invalid arguement
			throw new InvalidArgumentException('Incorrect Deanery in team '.$this->getName());
		}
		
		$this->deanery = $string;
	}
	function getDeanery() {
		return $this->deanery;
	}
	
	// function that looks up the deanery name from the database
	function setDeaneryFromTeam($team) {
		try {
			$cxn = new PDO(DB::dsn, DB::user, DB::password);
		
			// assume team name is not properly escaped
			$safe = $cxn->quote($this->name);
			$safe = strtr($safe,array('_' => '\_', '%' => '\%'));
			
			// is the team in the database
			$query = "SELECT count(*) FROM teams WHERE team = $safe";
			if($result = $cxn->query($query)) {
				if($result->fetchColumn()==0) { // no
					throw new Exception ('No deanery in the database');
				}
				// yes - get correct deanery and set the property
				$query = "SELECT deanery FROM teams WHERE team = $safe";
				$result = $cxn->query($query);
				$result->execute();
				$row = $result->fetch(PDO::FETCH_NUM);
				$this->deanery = $row[0];
			} else {
				$this->deanery = "";
			}
			
			$cxn = null;
		} catch (PDOException $e) {
			throw new InvalidArgumentException ('DB connection error ('.$cxn->connect_errno.') '.$cxn->connect_error.'\n');
		} catch (Exception $e) { // treat both errors as an invalid arguement
			// tidy up the mysql connection before throwing the exception
			$cxn = null;
			throw new InvalidArgumentException('Team '.$this->getName().' can\'t find deanery');
		}
	}
	
	// constrcutors
	function __construct() {
		if(func_num_args()==2) {
			// team name first and then deanery
			$this->setName(func_get_arg(0));
			$this->setDeanery(func_get_arg(1));
		} elseif(func_num_args()==1) {
			// team name only
			$this->setName(func_get_arg(0));
			$this->setDeaneryFromTeam(func_get_arg(0));
		} else {
			// empty constructor
			$this->name = "";
			$this->deanery = "";
		}
	}

}
?>