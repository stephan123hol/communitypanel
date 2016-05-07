<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/profile.class.php");

class Stats extends Object
{
	private $_profile;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_profile = new Profile();
	}
	
	public function getTopPromos($amount, $date)
	{
		$query = sprintf(
			"	SELECT		COUNT(id) AS amount,
							`paneel_rangverandering`.`rang_door`
				FROM		`paneel_rangverandering`
				WHERE 		DATE_FORMAT(`rang_op`, '%%Y-%%m') = '%s'
					AND 	`paneel_rangverandering`.`rang_soort` = 'Promotie'
				GROUP BY 	`paneel_rangverandering`.`rang_door`
				ORDER BY 	`amount` DESC
				LIMIT 		%d",
			$date,
			$amount
		);
		$result = $this->getConnection()->query($query);
		
		$data = array();
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$amount,
					$by
				) = $this->getConnection()->fetch_row($result)
			)
			{	
				$i = count($data);
				
				$data[$i]['amount'] 	= $amount;
				$data[$i]['by'] 		= $by;
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getTopTrainings($amount, $date)
	{
		$query = sprintf(
			"	SELECT		COUNT(id) AS amount,
							`paneel_trainingen`.`door`
				FROM		`paneel_trainingen`
				WHERE 		DATE_FORMAT(`datum`, '%%Y-%%m') = '%s'
				GROUP BY 	`paneel_trainingen`.`door`
				ORDER BY 	`amount` DESC
				LIMIT 		%d",
			$date,
			$amount
		);
		$result = $this->getConnection()->query($query);
		
		$data = array();
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$amount,
					$by
				) = $this->getConnection()->fetch_row($result)
			)
			{	
				$i = count($data);
				
				$data[$i]['amount'] 	= $amount;
				$data[$i]['by'] 		= $by;
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getMonth($month)
	{
		$i 		= ($month - 1);
		$months = array(
			"januari",
			"februari",
			"maart",
			"april",
			"mei",
			"juni",
			"juli", 
			"augustus",
			"september",
			"oktober",
			"november",
			"december"
		);
		
		return $months[$i];
	}
	
	public function getDonators()
	{
		$query = sprintf(
			"	SELECT		`leden`.`donation_amount`,
							`leden`.`habbonaam`
				FROM		`leden`
				WHERE 		`leden`.`donator` = 1
				ORDER BY 	`leden`.`donation_amount` DESC"
		);
		$result = $this->getConnection()->query($query);
		
		$data = array();
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$amount,
					$username
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				$data[$i]['amount'] 	= $amount;
				$data[$i]['username'] 	= $username;
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getPromotions($showAmount)
	{
		$query = sprintf(
			"	SELECT		`paneel_rangverandering`.`rang_oud`,
							`paneel_rangverandering`.`rang_nieuw`,
							`paneel_rangverandering`.`habbonaam`,
							`paneel_rangverandering`.`rang_door`,
							`paneel_rangverandering`.`rang_op`,
							`paneel_rangverandering`.`rang_soort`,
							`paneel_rangverandering`.`reden`
				FROM		`paneel_rangverandering`
				ORDER BY	`paneel_rangverandering`.`id` DESC
				LIMIT 		%d",
			$showAmount
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		$tag  = $this->_profile->getPromotag($username);
		
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$rang_oud,
					$rang_nieuw,
					$habbonaam,
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
					$data[$i]['rang_oud'] 	= $this->_profile->getRankName($rang_oud);
				}
				
				$data[$i]['rang_nieuw'] = $this->_profile->getRankName($rang_nieuw);
				$data[$i]['habbonaam']	= $habbonaam;
				$data[$i]['rang_door']	= $rang_door;
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
	
	public function getTrainings($showAmount)
	{
		$query = sprintf(
			"	SELECT		`paneel_trainingen`.`training`,
							`paneel_trainingen`.`gehaald`,
							`paneel_trainingen`.`habbonaam`,
							`paneel_trainingen`.`door`,
							`paneel_trainingen`.`datum`
				FROM		`paneel_trainingen`
				WHERE 		`paneel_trainingen`.`wis` = 0
				ORDER BY	`paneel_trainingen`.`id` DESC
				LIMIT 		%d",
			$showAmount
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
					$door,
					$datum
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				$data[$i]['training']  = $training;
				$data[$i]['habbonaam'] = $habbonaam;
				$data[$i]['door']	   = $door;
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
	
	public function listLatestRanks()
	{
		$query = sprintf(
			"	SELECT		`paneel_personeel`.`habbonaam`
				FROM		`paneel_personeel`"
		);
		$result = $this->getConnection()->query($query);
		while (
			list(
				$habbonaam
			) = $this->getConnection()->fetch_row($result)
		)
		{
			$query2 = sprintf(
				"	SELECT		`paneel_rangverandering`.`rang_nieuw`
					FROM		`paneel_rangverandering`
					WHERE		`paneel_rangverandering`.`habbonaam` = '%s'
					ORDER BY 	`paneel_rangverandering`.`id` DESC
					LIMIT		1;",
				$habbonaam
			);
			$result2 = $this->getConnection()->query($query2);
			
			while (list($rangNieuw) = $this->getConnection()->fetch_row($result2))
			{
				$rank = $this->getRankName($rangNieuw);
				$i    = count($data[$rank]);
				
				if (count($data[$rank]["level"]) == 0)
				{
					$data[$rank]["level"] = $rangNieuw;
				}
				
				$data[$rank]['leden'][] = $habbonaam;
				
				uasort($data, array("Stats", "sortArray"));
			}
		}
		
		return $data;
	}
	
	static function sortArray($a, $b)
	{
		return $b["level"] - $a["level"];
	}
	
	public function getRankName($rank)
	{
		$query = sprintf(
			"	SELECT	`paneel_rangniveau`.`rang_naam`
				FROM	`paneel_rangniveau`
				WHERE	`paneel_rangniveau`.`rang_level` = %d
				LIMIT	1;",
			$rank
		);

		$result = $this->getConnection()->query($query);
		list($rank) = $this->getConnection()->fetch_row($result);
		
		return $rank;
	}
}