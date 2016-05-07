<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

class Forum extends Object
{
	private $_user;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_user = new User();
	}
	
	public function getTopicVar($topicId, $var)
	{
		$query = sprintf(
			"	SELECT	`paneel_FTopic`.`%s`
				FROM	`paneel_FTopic`
				WHERE	`paneel_FTopic`.`id` = %d
				LIMIT	1",
			$this->getConnection()->escape($var),
			$topicId
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function getCommentVar($commentId, $var)
	{
		$query = sprintf(
			"	SELECT	`paneel_FTopic`.`%s`
				FROM	`paneel_FTopic`
				WHERE	`paneel_FTopic`.`id` = %d
				LIMIT	1",
			$this->getConnection()->escape($var),
			$topicId
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function getLastCommentVarByTopic($topicId, $var)
	{
		$query = sprintf(
			"	SELECT		`paneel_FReactie`.`%s`
				FROM		`paneel_FReactie`
				WHERE		`paneel_FReactie`.`topic` = %d
					AND 	`paneel_FReactie`.`prullenbak` = 0
				ORDER BY 	`paneel_FReactie`.`datum` DESC
				LIMIT 		1",
			$this->getConnection()->escape($var),
			$topicId
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function countReactions($topicId)
	{
		$query = sprintf(
			"	SELECT	COUNT(`id`)
				FROM	`paneel_FReactie`
				WHERE	`paneel_FReactie`.`topic` = %d
				AND 	`paneel_FReactie`.`prullenbak` = 0",
			$topicId
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		return $amount;
	}
	
	public function countWatched($topicId)
	{
		$query = sprintf(
			"	SELECT	COUNT(`id`)
				FROM	`paneel_FGelezen`
				WHERE	`paneel_FGelezen`.`topic` = %d
				AND 	`paneel_FGelezen`.`prullenbak` = 0",
			$topicId
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		return $amount;
	}
	
	public function updateWatched($username, $topicId)
	{
		$queryUser = sprintf(
			"	SELECT		`paneel_FGelezen`.`datum`
				FROM		`paneel_FGelezen`
				WHERE		`paneel_FGelezen`.`habbonaam` = '%s'
					AND 	`paneel_FGelezen`.`topic` = %d
					AND 	`paneel_FGelezen`.`prullenbak` = 0
				ORDER BY 	`paneel_FGelezen`.`datum` DESC
				LIMIT 		1",
			$this->getConnection()->escape($username),
			$topicId
		);
		$resultUser = $this->getConnection()->query($queryUser);
		$amountUser = $this->getConnection()->num_rows($resultUser);
		
		if ($amountUser == 0)
		{
			$queryInsert = sprintf(
				"	INSERT INTO `paneel_FGelezen` (`habbonaam`, `topic`, `datum`)
					VALUES 		('%s', %d, NOW())",
				$this->getConnection()->escape($username),
				$topicId
			);
			$resultInsert = $this->getConnection()->query($queryInsert);
		}
		else
		{
			list($dateUser)	= $this->getConnection()->fetch_row($resultUser);
			
			if ($this->countReactions($topicId) == 0)
			{
				$lastDate = $this->getTopicVar($topicId, "datum");
			}
			else
			{
				$lastDate = $this->getLastCommentVarByTopic($topicId, "datum");
			}
			
			if ($dateUser < $lastDate)
			{
				$queryUpdate = sprintf(
					"	UPDATE 		`paneel_FGelezen`
						SET 		`paneel_FGelezen`.`datum` = NOW()
						WHERE 		`paneel_FGelezen`.`habbonaam` = '%s'
							AND 	`paneel_FGelezen`.`topic` = %d",
					$this->getConnection()->escape($username),
					$topicId
				);
				$resultUpdate = $this->getConnection()->query($queryUpdate);
			}
		}
	}
	
	public function getReactionAmounts($categoryId)
	{
		$query = sprintf(
			"	SELECT 		COUNT(`paneel_FReactie`.`id`)
				FROM 		`paneel_FReactie`
				INNER JOIN 	`paneel_FTopic` ON `paneel_FReactie`.`topic` = `paneel_FTopic`.`id`
				WHERE 		`paneel_FTopic`.`categorie` = %d",
			$categoryId
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		return $amount;
	}
	
	public function getTopicAmounts($categoryId)
	{
		$query = sprintf(
			"	SELECT 		COUNT(`paneel_FTopic`.`id`)
				FROM 		`paneel_FTopic`
				WHERE 		`paneel_FTopic`.`categorie` = %d
					AND 	`paneel_FTopic`.`prullenbak` = 0",
			$categoryId
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		return $amount;
	}
	
	public function signatureEnabled($username)
	{
		$response = $this->_user->getUserVar($username, "signature_enabled");
		
		if ($response == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getSignature($username)
	{
		$signature = $this->_user->getUserVar($username, "signature");
		
		return $signature;
	}
	
	public function deleteTopicComments($topicId)
	{
		$queryUpdate = sprintf(
			"	UPDATE 		`paneel_FReactie`
				SET 		`paneel_FReactie`.`prullenbak` = 1
				WHERE 		`paneel_FReactie`.`topic` = '%d'",
			$topicId
		);
		$resultUpdate = $this->getConnection()->query($queryUpdate);
	}
	
	public function getUserDepartmentLevel($username, $departmentId)
	{
		$query = sprintf(
			"	SELECT 		COUNT(`paneel_FLeden`.`id`),
							`paneel_FLeden`.`level`
				FROM 		`paneel_FLeden`
				WHERE 		`paneel_FLeden`.`habbonaam` = '%s'
					AND 	`paneel_FLeden`.`departement` = %d",
			$this->getConnection()->escape($username),
			$departmentId
		);
		$result = $this->getConnection()->query($query);
		
		list($amount, $level) = $this->getConnection()->fetch_row($result);
		
		if ($amount > 0)
		{
			return $level;
		}
		else
		{
			$userLevel = $this->_user->getUserLevel($username);
			
			if ($userLevel > 1)
			{
				return 5;
			}
			else if ($userLevel == 1)
			{
				return 4;
			}
			else
			{
				return 0;
			}
		}
	}
	
	public function checkDepartmentPermission($username, $departmentId, $permission)
	{
		$level = $this->getUserDepartmentLevel($username, $departmentId);
		
		$query = sprintf(
			"	SELECT	`paneel_FLevel`.`%s`
				FROM	`paneel_FLevel`
				WHERE	`paneel_FLevel`.`level` = '%s'
				LIMIT	1;",
			$this->getConnection()->escape($permission),
			$this->getConnection()->escape($level)
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		if ($response == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}