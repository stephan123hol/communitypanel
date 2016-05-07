<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

class Profile extends Object
{
	private $_user;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_user = new User();
	}
	
	public function hasProfile($username)
	{
		$queryPromotions = sprintf(
			"	SELECT		`paneel_rangverandering`.`id`
				FROM		`paneel_rangverandering`
				WHERE		`paneel_rangverandering`.`habbonaam` = '%s'
				LIMIT	1",
			$this->getConnection()->escape($username)
		);
		$resultPromotions = $this->getConnection()->query($queryPromotions);
		$countPromotions  = $this->getConnection()->num_rows($resultPromotions);		
		if ($countPromotions > 0)
		{
			return true;
		}
		else
		{
			$queryTrainings = sprintf(
				"	SELECT		`paneel_trainingen`.`id`
					FROM		`paneel_trainingen`
					WHERE		`paneel_trainingen`.`habbonaam` = '%s'
					LIMIT	1",
				$this->getConnection()->escape($username)
			);
			$resultTrainings = $this->getConnection()->query($queryTrainings);
			$countTrainings  = $this->getConnection()->num_rows($resultTrainings);
			
			if ($countTrainings > 0)
			{
				return true;
			}
			else
			{
				$queryMember = sprintf(
					"	SELECT		`leden`.`id`
						FROM		`leden`
						WHERE		`leden`.`habbonaam` = '%s'
						LIMIT	1",
					$this->getConnection()->escape($username)
				);
				$resultMember = $this->getConnection()->query($queryMember);
				$countMember  = $this->getConnection()->num_rows($resultMember);
				
				if ($countMember > 0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	
	public function generateActions($targetUser, $currentUser)
	{
		$actions   = array();
		$hipChatID = $this->_user->getUserVar($targetUser, "hipchat_user_id");
		
		if (!is_null($hipChatID))
		{
			if ($this->_user->checkPermission($currentUser, "grant_pw_change") === true)
			{
				$actions["hipchat-change-pw"] = "Verander HipChat wachtwoord";
			}
		}
		else
		{
			if ($this->_user->checkPermission($currentUser, "hipchat_user_maken") === true)
			{
				$actions["create-hipchat-account"] = "Maak HipChat account aan";
			}
		}
		
		if ($this->_user->checkPermission($currentUser, "toegang_promotie") === true)
		{
			if ($this->_user->inPromotionLimit($targetUser, $currentUser) === true)
			{
				$actions["promote"] = "Promoveren";
			}
		}
		
		if ($this->_user->checkPermission($currentUser, "toegang_degradatie") === true)
		{
			if ($this->_user->inDemotionLimit($targetUser, $currentUser) === true)
			{
				$actions["demote"] = "Degraderen";
			}
		}
		
		if ($this->_user->checkPermission($_SESSION["habbonaam"], "grant_pw_change") === true && $this->_user->userExists($targetUser) === true)
		{
			if ($this->_user->getUserVar($targetUser, "allow_pw_change") == 0)
			{
				$actions["allow-pw-change"] = "Mag wachtwoord veranderen";
			}
		}
		
		return $actions;
	}
	
	public function getRankName($level)
	{
		$query = sprintf(
			"	SELECT	`paneel_rangniveau`.`rang_naam`
				FROM	`paneel_rangniveau`
				WHERE	`paneel_rangniveau`.`rang_level` = %d
				LIMIT	1;",
			$level
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function getPromotag($username)
	{
		$query = sprintf(
			"	SELECT	`paneel_promotag`.`promotag`
				FROM	`paneel_promotag`
				WHERE	`paneel_promotag`.`habbonaam` = '%s'
					AND	`paneel_promotag`.`wis` = 0
				LIMIT	1;",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function getPromotions($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_rangverandering`.`rang_oud`,
							`paneel_rangverandering`.`rang_nieuw`,
							`paneel_rangverandering`.`rang_door`,
							`paneel_rangverandering`.`rang_op`,
							`paneel_rangverandering`.`rang_soort`,
							`paneel_rangverandering`.`reden`
				FROM		`paneel_rangverandering`
				WHERE 		`paneel_rangverandering`.`habbonaam` = '%s'
				ORDER BY	`paneel_rangverandering`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		$data = array();		
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$rang_oud,
					$rang_nieuw,
					$rang_door,
					$rang_op,
					$rang_soort,
					$reden
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				if ($rang_oud == 0)
				{
					$data[$i]['rang_oud'] = "Geen rang";
				}
				else
				{
					$data[$i]['rang_oud'] 	= $this->getRankName($rang_oud);
				}
				
				$data[$i]['rang_nieuw'] = $this->getRankName($rang_nieuw);
				$data[$i]['rang_door']	= $rang_door;
				$data[$i]['rang_op'] 	= $rang_op;
				$data[$i]['rang_soort'] = $rang_soort;
				$data[$i]['reden'] 		= $reden;
				$data[$i]['promotag'] 	= $this->getPromotag($rang_door);
				
				switch ($rang_soort) {
					case "Promotie":
						$data[$i]["icon"] = "plus";
						break;
					case "Degradatie":
						$data[$i]["icon"] = "minus";
						break;
					case "Ontslag":
						$data[$i]["icon"] = "ban";
						break;
					case "Herstel":
						$data[$i]["icon"] = "wrench";
						break;
				}
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getGivenPromotions($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_rangverandering`.`rang_oud`,
							`paneel_rangverandering`.`rang_nieuw`,
							`paneel_rangverandering`.`habbonaam`,
							`paneel_rangverandering`.`rang_op`,
							`paneel_rangverandering`.`rang_soort`,
							`paneel_rangverandering`.`reden`
				FROM		`paneel_rangverandering`
				WHERE 		`paneel_rangverandering`.`rang_door` = '%s'
				ORDER BY	`paneel_rangverandering`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		$data = array();
		$tag  = $this->getPromotag($username);
		
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$rang_oud,
					$rang_nieuw,
					$habbonaam,
					$rang_op,
					$rang_soort,
					$reden
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				if ($rang_oud == 0)
				{
					$data[$i]['rang_oud'] = "Geen rang";
				}
				else
				{
					$data[$i]['rang_oud'] 	= $this->getRankName($rang_oud);
				}
				
				$data[$i]['rang_nieuw'] = $this->getRankName($rang_nieuw);
				$data[$i]['habbonaam']	= $habbonaam;
				$data[$i]['rang_op'] 	= $rang_op;
				$data[$i]['rang_soort'] = $rang_soort;
				$data[$i]['reden'] 		= $reden;
				$data[$i]['promotag'] 	= $tag;
				
				switch ($rang_soort) {
					case "Promotie":
						$data[$i]["icon"] = "plus";
						break;
					case "Degradatie":
						$data[$i]["icon"] = "minus";
						break;
					case "Ontslag":
						$data[$i]["icon"] = "ban";
						break;
					case "Herstel":
						$data[$i]["icon"] = "wrench";
						break;
				}
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getTrainings($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_trainingen`.`training`,
							`paneel_trainingen`.`gehaald`,
							`paneel_trainingen`.`door`,
							`paneel_trainingen`.`datum`
				FROM		`paneel_trainingen`
				WHERE 		`paneel_trainingen`.`habbonaam` = '%s'
					AND 	`paneel_trainingen`.`wis` = 0
				ORDER BY	`paneel_trainingen`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		$tag  = $this->getPromotag($username);
		
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$training,
					$gehaald,
					$door,
					$datum
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				$data[$i]['training'] = $training;
				$data[$i]['door'] 	  = $door;
				$data[$i]['datum'] 	  = $datum;
				
				switch ($gehaald) {
					case 0:
						$data[$i]["icon"] = "times";
						break;
					case 1:
						$data[$i]["icon"] = "check";
						break;
				}
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getGivenTrainings($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_trainingen`.`training`,
							`paneel_trainingen`.`gehaald`,
							`paneel_trainingen`.`habbonaam`,
							`paneel_trainingen`.`datum`
				FROM		`paneel_trainingen`
				WHERE 		`paneel_trainingen`.`door` = '%s'
					AND 	`paneel_trainingen`.`wis` = 0
				ORDER BY	`paneel_trainingen`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$training,
					$gehaald,
					$habbonaam,
					$datum
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				$data[$i]['training']  = $training;
				$data[$i]['habbonaam'] = $habbonaam;
				$data[$i]['datum'] 	   = $datum;
				
				switch ($gehaald) {
					case 0:
						$data[$i]["icon"] = "times";
						break;
					case 1:
						$data[$i]["icon"] = "check";
						break;
				}
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getWarnings($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_warn`.`warn`,
							`paneel_warn`.`warn_gever`,
							`paneel_warn`.`warn_op`
				FROM		`paneel_warn`
				WHERE 		`paneel_warn`.`warn_ontvanger` = '%s'
					AND 	`paneel_warn`.`wis` = 0
				ORDER BY	`paneel_warn`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		$tag  = $this->getPromotag($username);
		
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$warn,
					$warn_gever,
					$warn_op
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				$data[$i]['warn'] 		= $warn;
				$data[$i]['warn_gever']	= $warn_gever;
				$data[$i]['warn_op'] 	= $warn_op;
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function countPromotions($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_rangverandering`.`id`
				FROM		`paneel_rangverandering`
				WHERE 		`paneel_rangverandering`.`habbonaam` = '%s'
				ORDER BY	`paneel_rangverandering`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		return $this->getConnection()->num_rows($result);
	}
	
	public function countGivenPromotions($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_rangverandering`.`id`
				FROM		`paneel_rangverandering`
				WHERE 		`paneel_rangverandering`.`rang_door` = '%s'
				ORDER BY	`paneel_rangverandering`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		return $this->getConnection()->num_rows($result);
	}
	
	public function countTrainings($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_trainingen`.`id`
				FROM		`paneel_trainingen`
				WHERE 		`paneel_trainingen`.`habbonaam` = '%s'
					AND 	`paneel_trainingen`.`wis` = 0
				ORDER BY	`paneel_trainingen`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		return $this->getConnection()->num_rows($result);
	}
	
	public function countGivenTrainings($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_trainingen`.`id`
				FROM		`paneel_trainingen`
				WHERE 		`paneel_trainingen`.`door` = '%s'
					AND 	`paneel_trainingen`.`wis` = 0
				ORDER BY	`paneel_trainingen`.`id` DESC",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		return $this->getConnection()->num_rows($result);
	}
}