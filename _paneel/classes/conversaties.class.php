<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

class Conversaties extends Object
{
	private $_user;
	private $_inputFilter;
	
	public function __construct()
	{
		parent::__construct();

		$this->_user 		= new User();
		$this->_inputFilter = new InputFilter("panelify");
	}
	
	public function listConversations($session_id)
	{
		$query = sprintf(
			"	SELECT		`paneel_convo_participants`.`conversation_id`
				FROM		`paneel_convo_participants`
				INNER JOIN	`paneel_conversaties`
				ON			`paneel_convo_participants`.`conversation_id` = `paneel_conversaties`.`id`
				WHERE		`paneel_convo_participants`.`user_id` = %d
				ORDER BY	`paneel_conversaties`.`last_reply` DESC;",
			$session_id
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while(list($convo_id) = $this->getConnection()->fetch_row($result))
			{
				$query2 = sprintf(
					"	SELECT		`paneel_conversaties`.`title`,
									`paneel_conversaties`.`starter`,
									`paneel_conversaties`.`created_on`,
									`paneel_conversaties`.`closed`
						FROM		`paneel_conversaties`
						WHERE		`paneel_conversaties`.`id` = %d	
						LIMIT	1;",
					$convo_id
				);
				$result2 = $this->getConnection()->query($query2);
				
				while (
					list(
						$title,
						$starter,
						$created_on,
						$closed
					) = $this->getConnection()->fetch_row($result2)
				)
				{
					$i = count($data);
					
					$data[$i]['id'] 		= $convo_id;
					$data[$i]['title'] 		= $title;
					$data[$i]['starter'] 	= $this->_user->idToUsername($starter);
					$data[$i]['created_on'] = $created_on;
					$data[$i]['closed'] 	= $closed;
					
					if ($closed == 1)
					{
						$data[$i]['icon'] = "lock";
					}
					
					$participants = $this->getParticipants($convo_id);
					$data[$i]['participants'] = "";
					
					$data[$i]['new_replies'] = $this->checkNewReplies($convo_id, $session_id);
					
					$participants_amount	= count($participants);
					$participant_number		= 0;
					
					foreach($participants as $value)
					{
						$participant_number++;
						$data[$i]['participants'] .=  $this->_user->idToUsername($value['user_id']);
						
						if ($participants_amount != $participant_number)
						{
							$data[$i]['participants'] .= ", ";
						}
					}
				}
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getConversation($convo_id)
	{
		$query = sprintf(
			"	SELECT	`paneel_conversatie_berichten`.`id`,
						`paneel_conversatie_berichten`.`message`,
						`paneel_conversatie_berichten`.`from_user`,
						`paneel_conversatie_berichten`.`timestamp`
				FROM	`paneel_conversatie_berichten`
				WHERE	`paneel_conversatie_berichten`.`conversation_id` = %d;",
			$convo_id
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while(list($id, $message, $from_user, $timestamp) = $this->getConnection()->fetch_row($result))
			{
				$i = count($data);
				
				$data[$i]['id'] 		= $id;
				$data[$i]['message'] 	= $message;
				$data[$i]['from_user'] 	= $from_user;
				$data[$i]['timestamp']	= $timestamp;
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
	
	public function getConversationData($convo_id)
	{
		$query = sprintf(
			"	SELECT	`paneel_conversaties`.`title`,
						`paneel_conversaties`.`starter`,
						`paneel_conversaties`.`created_on`,
						`paneel_conversaties`.`closed`
				FROM	`paneel_conversaties`
				WHERE	`paneel_conversaties`.`id` = %d;",
			$convo_id
		);
		$result = $this->getConnection()->query($query);
		
		while(
			list(
				$title, 
				$starter,
				$created_on,
				$closed
			) = $this->getConnection()->fetch_row($result)
		)
		{
			$data['title'] 		= $title;
			$data['starter'] 	= $starter;
			$data['created_on'] = $created_on;
			$data['closed'] 	= $closed;
		}
		
		return $data;
	}
	
	public function addMessage($session_id, $convo_id, $message)
	{
		if ($this->checkInConversation($session_id, $convo_id) === true)
		{
			$query = sprintf(
				"	INSERT INTO `paneel_conversatie_berichten` (`conversation_id`, `message`, `from_user`, `timestamp`)
					VALUES (%d, '%s', %d, %d)",
				$convo_id,
				$this->getConnection()->escape($this->_inputFilter->filterHTML($message)),
				$session_id,
				time()
			);
			$this->getConnection()->query($query);
			
			$query = sprintf(
				"	UPDATE	`paneel_conversaties`
					SET		`paneel_conversaties`.`last_reply` = %d
					WHERE	`paneel_conversaties`.`id` = %d;",
				time(),
				$convo_id
			);
			$this->getConnection()->query($query);
			$this->_user->logAction($session_id, "GLOBAL-ACTION", "convo-reply", null, $convo_id);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function newConversation($session_id, $title, $participants, $message, $close_on_create)
	{
		if (empty($participants))
		{
			$error = "Er zijn geen (geldige) ontvangers ingevuld.";
		}
		else
		{
			$participants_filtered = preg_replace('/\s+/', '', $participants);
			$explode = explode("+", $participants_filtered);
			
			if (count($explode) > 15)
			{
				$error = "Er mogen niet meer dan 15 mensen in een conversatie.";
			}
			else
			{
				if (isset($close_on_create) && $close_on_create == '1')
				{
					$closed = 1;
				}
				else
				{
					$closed = 0;
				}
				
				$query = sprintf(
					"	INSERT INTO `paneel_conversaties` (`title`, `starter`, `created_on`, `closed`)
						VALUES ('%s', %d, %d, %d)",
					strip_tags($this->getConnection()->escape($title)),
					$session_id,
					time(),
					$closed
				);
				$this->getConnection()->query($query);
				
				$last_id = $this->getConnection()->last_id();
				
				$query = sprintf(
					"	INSERT INTO `paneel_convo_participants` (`conversation_id`, `user_id`)
						VALUES (%d, %d)",
					$last_id,
					$session_id
				);
				$this->getConnection()->query($query);
				$this->_user->logAction($session_id, "GLOBAL-ACTION", "convo-adduser", $session_id, $convo_id);
				
				$users = array();
				foreach($explode as $value)
				{
					if (!in_array($users))
					{
						$this->addUserToConvo($session_id, $value, $last_id);
					}
					
					$users[] = $value;
				}
				
				$this->addMessage($session_id, $last_id, $message);
			}
		}
		
		if (isset($error))
		{
			$message = $error;
		}
		else
		{
			$message = true;
		}
		
		$this->_user->logAction($session_id, "GLOBAL-ACTION", "convo-new", null, $convo_id);
		return $message;
	}
	
	public function addUserToConvo($session_id, $username, $convo_id)
	{
		$user_id = $this->_user->usernameToId($username);
		
		if ($user_id != $session_id && $this->_user->userExists($username) === true)
		{
			$query = sprintf(
				"	INSERT INTO `paneel_convo_participants` (`conversation_id`, `user_id`)
					VALUES (%d, %d)",
				$convo_id,
				$user_id
			);
			$this->getConnection()->query($query);
			$this->_user->logAction($session_id, "GLOBAL-ACTION", "convo-adduser", $user_id, $convo_id);
		}
	}
	
	public function deleteUserFromConvo($session_id, $username, $convo_id)
	{
		$user_id = $this->_user->usernameToId($username);
		
		if ($user_id != $session_id && $this->checkInConversation($user_id, $convo_id) === true)
		{
			$query = sprintf(
				"	DELETE FROM `paneel_convo_participants`
					WHERE		`paneel_convo_participants`.`conversation_id` = %d
					AND			`paneel_convo_participants`.`user_id` = %d;",
				$convo_id,
				$user_id
			);
			$this->getConnection()->query($query);
			$this->_user->logAction($session_id, "GLOBAL-ACTION", "convo-removeuser", $user_id, $convo_id);
		}
	}
	
	public function leaveConversation($session_id, $user_id, $convo_id)
	{
		if ($user_id == $session_id && $this->checkInConversation($user_id, $convo_id) === true)
		{
			$query = sprintf(
				"	DELETE FROM `paneel_convo_participants`
					WHERE		`paneel_convo_participants`.`conversation_id` = %d
					AND			`paneel_convo_participants`.`user_id` = %d;",
				$convo_id,
				$user_id
			);
			$this->getConnection()->query($query);
			$this->_user->logAction($session_id, "GLOBAL-ACTION", "convo-leave", $user_id, $convo_id);
		}
	}
	
	public function getParticipants($convo_id)
	{
		$query = sprintf(
			"	SELECT	`paneel_convo_participants`.`user_id`
				FROM	`paneel_convo_participants`
				WHERE	`paneel_convo_participants`.`conversation_id` = %d;",
			$convo_id
		);
		$result = $this->getConnection()->query($query);
		
		$data = array();
		while(list($user_id) = $this->getConnection()->fetch_row($result))
		{
			$i 						= count($data);
			$data[$i]['user_id'] 	= $user_id;
		}
		
		return $data;
	}
	
	public function countMessages($convo_id)
	{
		$query = sprintf(
			"	SELECT	COUNT(`id`)
				FROM	`paneel_conversatie_berichten`
				WHERE	`paneel_conversatie_berichten`.`conversation_id` = %d;",
			$convo_id
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		return $amount;
	}
	
	public function countParticipants($convo_id)
	{
		$query = sprintf(
			"	SELECT	COUNT(`id`)
				FROM	`paneel_convo_participants`
				WHERE	`paneel_convo_participants`.`conversation_id` = %d;",
			$convo_id
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		return $amount;
	}
	
	public function getLastPost($convo_id)
	{
		$query = sprintf(
			"	SELECT		`paneel_conversatie_berichten`.`id`,
							`paneel_conversatie_berichten`.`from_user`,
							`paneel_conversatie_berichten`.`timestamp`
				FROM		`paneel_conversatie_berichten`
				WHERE		`paneel_conversatie_berichten`.`conversation_id` = %d
				ORDER BY	`paneel_conversatie_berichten`.`timestamp` ASC;",
			$convo_id
		);
		$result = $this->getConnection()->query($query);
		
		$data = array();
		while(list($id, $from_user, $timestamp) = $this->getConnection()->fetch_row($result))
		{
			$data['id'] 		= $id;
			$data['from_user'] 	= $from_user;
			$data['timestamp'] 	= $timestamp;
		}
		
		return $data;
	}
	
	public function checkInConversation($session_id, $convo_id)
	{
		$query = sprintf(
			"	SELECT	COUNT(`id`)
				FROM	`paneel_convo_participants`
				WHERE	`paneel_convo_participants`.`conversation_id` = %d
				AND		`paneel_convo_participants`.`user_id` = %d
				LIMIT	1;",
			$convo_id,
			$session_id
		);
		$result = $this->getConnection()->query($query);

		list($answer) = $this->getConnection()->fetch_row($result);
		
		if ($answer > 0)
		{
			$data = true;
		}
		else
		{
			if ($this->_user->isAdmin($_SESSION["habbonaam"]))
			{
				$data = true;
			}
			else
			{
				$data = false;
			}
		}
		
		return $data;
	}
	
	public function updateLastViewed($convo_id, $session_id)
	{
		$query = sprintf(
			"	UPDATE	`paneel_convo_participants`
				SET		`paneel_convo_participants`.`last_viewed` = %d
				WHERE	`paneel_convo_participants`.`conversation_id` = %d
				AND		`paneel_convo_participants`.`user_id` = %d;",
			time(),
			$convo_id,
			$session_id
		);
		$this->getConnection()->query($query);
		
		return true;
	}
	
	public function checkNewReplies($convo_id, $session_id)
	{
		$query = sprintf(
			"	SELECT	`paneel_convo_participants`.`last_viewed`
				FROM	`paneel_convo_participants`
				WHERE	`paneel_convo_participants`.`conversation_id` = %d
				AND		`paneel_convo_participants`.`user_id` = %d
				LIMIT	1;",
			$convo_id,
			$session_id
		);
		$result = $this->getConnection()->query($query);
		list($data) = $this->getConnection()->fetch_row($result);
		
		$query = sprintf(
			"	SELECT	DISTINCT `paneel_conversatie_berichten`.`conversation_id` = %d
				FROM	`paneel_conversatie_berichten`
				WHERE	`paneel_conversatie_berichten`.`conversation_id` = %d
				AND		`paneel_conversatie_berichten`.`timestamp` > %d
				LIMIT	1;",
			$convo_id,
			$convo_id,
			$data
		);
		$result = $this->getConnection()->query($query);
		$count 	= $this->getConnection()->num_rows($result);
		
		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function checkUnreadConversations($session_id)
	{
		$query = sprintf(
			"	SELECT		`paneel_convo_participants`.`last_viewed`
				FROM		`paneel_convo_participants`
				INNER JOIN	`paneel_conversatie_berichten`
				ON			`paneel_convo_participants`.`conversation_id` = `paneel_conversatie_berichten`.`conversation_id`
				WHERE		`paneel_convo_participants`.`user_id` = %d
				AND			`paneel_conversatie_berichten`.`timestamp` > `paneel_convo_participants`.`last_viewed`;",
			$session_id
		);
		$result = $this->getConnection()->query($query);
		$count 	= $this->getConnection()->num_rows($result);
		
		$answer = "".$count;
		
		return $answer;
	}
}
?>