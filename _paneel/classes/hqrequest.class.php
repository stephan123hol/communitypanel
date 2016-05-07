<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

class HqRequest extends Object
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getLastRequest($hq)
	{
		if ($hq == 1)
		{
			$setting = "last_hq1_request";
		}
		else
		{
			$setting = "last_hq2_request";
		}
		
		$query = sprintf(
			"	SELECT	`paneel_settings`.`content`
				FROM	`paneel_settings`
				WHERE	`paneel_settings`.`name` = '%s'
				LIMIT	1;",
			$this->getConnection()->escape($setting)
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function setLastRequest($hq)
	{
		if ($hq == 1)
		{
			$setting = "last_hq1_request";
		}
		else
		{
			$setting = "last_hq2_request";
		}
		
		$query = sprintf(
			"	UPDATE	`paneel_settings`
				SET		`paneel_settings`.`content` = NOW()
				WHERE	`paneel_settings`.`name` = '%s'",
			$this->getConnection()->escape($setting)
		);
		$result = $this->getConnection()->query($query);
	}
	
	public function checkTimeout($hq)
	{
		$lastRequest = $this->getLastRequest($hq);
		
		$start = new DateTime($lastRequest);
		$end   = $start->diff(new DateTime());
		
		return $end->i > 5;
	}
}