<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/conversaties.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/user.hc.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/rooms.hc.class.php");

class User extends Object
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function loggedIn()
	{
		if (isset($_SESSION["habbonaam"]) && $_SESSION["habbonaam"] != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function idToUsername($user_id)
	{
		$query = sprintf(
			"	SELECT	`leden`.`habbonaam`
				FROM	`leden`
				WHERE	`leden`.`id` = %d
				LIMIT	1;",
			$user_id
		);
		$result = $this->getConnection()->query($query);
		
		list($habbonaam) = $this->getConnection()->fetch_row($result);
		
		return $habbonaam;
	}
	
	public function usernameToId($username)
	{
		$query = sprintf(
			"	SELECT	`leden`.`id`
				FROM	`leden`
				WHERE	`leden`.`habbonaam` = '%s'
				LIMIT	1;",
			$username
		);
		$result = $this->getConnection()->query($query);
		
		list($id) = $this->getConnection()->fetch_row($result);
		
		return $id;
	}
	
	public function createUser($username, $hashedPassword)
	{
		$query = sprintf(
			"	INSERT INTO `leden` (`habbonaam`, `wachtwoord`, `ip`, `regdatum`, `lastonline`)
				VALUES		('%s', '%s', '%s', NOW(), NOW())",
			$this->getConnection()->escape($username),
			$this->getConnection()->escape($hashedPassword),
			$_SERVER['REMOTE_ADDR']
		);
		echo $query;
		$this->getConnection()->query($query);
	}
	
	public function getUserVar($username, $var)
	{
		$query = sprintf(
			"	SELECT	`leden`.`%s`
				FROM	`leden`
				WHERE	`leden`.`habbonaam` = '%s'
				LIMIT	1;",
			$this->getConnection()->escape($var),
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function setUserVar($var, $value, $username)
	{
		$query = sprintf(
			"	UPDATE	`leden`
				SET		`leden`.`%s` = '%s'
				WHERE	`leden`.`habbonaam` = '%s'",
			$this->getConnection()->escape($var),
			$this->getConnection()->escape($value),
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
	}
	
	public function unlinkHipChat($username)
	{
		$query = sprintf(
			"	UPDATE	`leden`
				SET		`leden`.`hipchat_user_id` = null
				WHERE	`leden`.`habbonaam` = '%s'",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
	}
	
	public function getRankLevelVar($rankName, $var)
	{
		$query = sprintf(
			"	SELECT	`paneel_rangniveau`.`%s`
				FROM	`paneel_rangniveau`
				WHERE	`paneel_rangniveau`.`rang_naam` = '%s'
				LIMIT	1;",
			$this->getConnection()->escape($var),
			$this->getConnection()->escape($rankName)
		);
		$result = $this->getConnection()->query($query);
		
		list($response) = $this->getConnection()->fetch_row($result);
		
		return $response;
	}
	
	public function sanitizeName($name)
	{
		return htmlspecialchars($name);
	}
	
	public function checkPermission($username, $permissie)
	{
		$query = sprintf(
			"	SELECT	`paneel_personeel`.`%s`
				FROM	`paneel_personeel`
				WHERE	`paneel_personeel`.`habbonaam` = '%s'
				LIMIT	1;",
			$this->getConnection()->escape($permissie),
			$this->getConnection()->escape($username)
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
		
		return $response;
	}
	
	public function getUserLevel($username)
	{
		$query = sprintf(
			"	SELECT		`paneel_admins`.`access_level`
				FROM		`paneel_admins`
				WHERE		`paneel_admins`.`habbonaam` = '%s'
				ORDER BY 	`paneel_admins`.`id` DESC
				LIMIT	1",
			$username
		);
		
		$result = $this->getConnection()->query($query);
		$count	= $this->getConnection()->num_rows($result);

		if ($count > 0)
		{
			list($level) = $this->getConnection()->fetch_row($result);
			
			return $level;
		}
		else
		{
			return 0;
		}
	}
	
	public function isAdmin($username)
	{
		if ($this->getUserLevel($username) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function isForumAdmin($username)
	{
		$userLevel = $this->getUserLevel($username);
		
		return $userLevel > 1;
	}
	
	public function getFunction($username, $tag = null)
	{
		$query = sprintf(
			"	SELECT		`paneel_rangverandering`.`rang_nieuw`
				FROM		`paneel_rangverandering`
				WHERE		`paneel_rangverandering`.`habbonaam` = '%s'
				ORDER BY 	`paneel_rangverandering`.`id` DESC
				LIMIT	1",
			$username
		);
		$result = $this->getConnection()->query($query);
		$count	= $this->getConnection()->num_rows($result);

		if ($count > 0)
		{
			list($rank_new) = $this->getConnection()->fetch_row($result);
			
			$query = sprintf(
				"	SELECT	`paneel_rangniveau`.`rang_naam`
					FROM	`paneel_rangniveau`
					WHERE	`paneel_rangniveau`.`rang_level` = %d
					LIMIT	1",
				$rank_new
			);

			$result = $this->getConnection()->query($query);
			list($rank) = $this->getConnection()->fetch_row($result);
		}
		else
		{
			$rank = "Geen rank bekend.";
		}
		
		return $rank;
	}
	
	public function inPromotionLimit($targetUser, $currentUser)
	{
		$cuRankName = $this->getFunction($currentUser);
		$tuRankName = $this->getFunction($targetUser);
		
		$cuRankLevel = $this->getRankLevelVar($cuRankName, "rang_level");
		$tuRankLevel = $this->getRankLevelVar($tuRankName, "rang_level");
		
		$cuMaxPromotionLevel = $this->getRankLevelVar($cuRankName, "promoveren_tot");
		
		if ($cuMaxPromotionLevel > $tuRankLevel)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function inDemotionLimit($targetUser, $currentUser)
	{
		$cuRankName = $this->getFunction($currentUser);
		$tuRankName = $this->getFunction($targetUser);
		
		$cuRankLevel = $this->getRankLevelVar($cuRankName, "rang_level");
		$tuRankLevel = $this->getRankLevelVar($tuRankName, "rang_level");
		
		$cuMaxDemotionLevel = $this->getRankLevelVar($cuRankName, "degraderen_tot");
		
		if ($cuMaxDemotionLevel >= $tuRankLevel)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function userExists($username)
	{
		$query = sprintf(
			"	SELECT	COUNT(`leden`.`id`)
				FROM	`leden`
				WHERE	`leden`.`habbonaam` = '%s'
				LIMIT	1;",
			$this->getConnection()->escape($username)
		);
		$result = $this->getConnection()->query($query);
		
		list($amount) = $this->getConnection()->fetch_row($result);
		
		if ($amount == 1)
		{
			$answer = true;
		}
		else
		{
			$answer = false;
		}
		
		return $answer;
	}
	
	public function logAction($user, $tag, $action, $target_user = null, $extra_data = null)
	{
		$username = $this->idToUsername($user);
		$target_username = $this->idToUsername($target_user);
		
		switch ($action)
		{
			case "convo-adduser":
				$message = "heeft de gebruiker " . $target_username . " toegevoegd aan de conversatie (ID): ". $extra_data .".";
				break;
			case "convo-new":
				$message = "heeft een nieuwe conversatie gemaakt met ID: ". $extra_data .".";
				break;
			case "convo-removeuser":
				$message = "heeft de gebruiker " . $target_username . " verwijderd uit de conversatie (ID): ". $extra_data .".";
				break;
			case "convo-reply":
				$message = "heeft een nieuwe reactie geplaatst in convo (ID): ". $extra_data .".";
				break;
			case "convo-leave":
				$message = "heeft het gesprek (ID): ". $extra_data ." verlaten.";
				break;
			case "ban-username":
				$message = "heeft de gebruiker ". $extra_data ." verbannen.";
				break;
			case "ban-ip":
				$message = "heeft het IP ". $extra_data ." verbannen.";
				break;
			case "granted-pw-change":
				$message = "heeft voor gebruiker ". $extra_data ." toegestaan zijn wachtwoord te veranderen.";
				break;
			case "created-hipchat-account":
				$message = "heeft voor gebruiker ". $extra_data ." een HipChat account gemaakt.";
				break;
			case "hipchat-pw-change":
				$message = "heeft voor gebruiker ". $extra_data ." zijn HipChat wachtwoord veranderd.";
				break;
			case "wrong-login":
				$message = "heeft proberen in te loggen met een fout wachtwoord.";
				break;
			case "succesfull-login":
				$message = "heeft zichzelf ingelogd.";
				break;
			case "registered":
				$message = "heeft zichzelf geregistreerd.";
				break;
			case "user-pass-reset-succes":
				$message = "heeft zijn/haar wachtwoord aangepast.";
				break;
			case "user-pass-reset-wrong":
				$message = "heeft een foutieve poging gedaan het wachtwoord aan te passen van " . $target_user;
				break;
		}
		
		$query = sprintf(
			"	INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`)
				VALUES		('%s', '%s', '%s', '%s', NOW())",
			$this->getConnection()->escape($username),
			$this->getConnection()->escape($message),
			$_SERVER['REMOTE_ADDR'],
			$_SERVER['HTTP_USER_AGENT']
		);
		$this->getConnection()->query($query);
	}
	
	public function getDataFromHabboAPI($username, $var)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://www.habbo.nl/api/public/users?name=" . $username . "");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = json_decode(curl_exec($ch));
		curl_close($ch);
		
		return $output->{$var};
	}
	
	public function getDataFromHipChatAPI($username)
	{
		require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/user.hc.class.php");
		
		$HipChatUser = new HipChatAPIUser();
		$HipChatUserID = $this->getUserVar($username, "hipchat_user_id");
		
		return $HipChatUser->getData($HipChatUserID);
	}
	
	public function checkMotto($username, $code)
	{	
		if ($this->getDataFromHabboAPI($username, "motto") == $code) {
			return true;
		} else {
			return false;
		}
	}
	
	public function showHipChatTagname($username, $dev = false)
	{
		require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/user.hc.class.php");
		
		$HipChatUser = new HipChatAPIUser();
		$HipChatUserID = $this->getUserVar($username, "hipchat_user_id");
		
		if (!is_null($HipChatUserID))
		{
			$data   = $HipChatUser->getData($HipChatUserID);
			
			if ($data["is_deleted"] === false)
			{
				$presence = $data["presence"]["is_online"] === true ? 'online' : 'offline';
				$status = '<div class="presence-' . $presence . '"></div>';
			}
			else
			{
				$status = 'Account gedeactiveerd';
			}
			
			if ($status == "Account gedeactiveerd")
			{
				$response = $status;
			}
			else
			{
				$response = $status . "@" . $data["mention_name"];
			}
			
			if ($this->checkPermission($_SESSION["habbonaam"], "hipchat_user_maken") === true && $dev == false)
			{
				$response .= ' - <a id="hipchat-change-pw">Verander HipChat wachtwoord</a>';
			}
		}
		else
		{
			$response = "Geen HipChat account";
			
			if ($this->checkPermission($_SESSION["habbonaam"], "hipchat_user_maken") === true && $dev == false)
			{
				$response .= ' - <a id="create-hipchat-account">Maak account</a>';
			}
		}
		
		return $response;
	}
	
	public function createHipChatUser($username)
	{
		$HipChatAPIUser  = new HipChatAPIUser();
		$HipChatAPIRooms = new HipChatAPIRooms();
		$conversaties 	 = new Conversaties();
		$userRank     	 = $this->getFunction($username);	
		$userData 	  	 = $HipChatAPIUser->createUser($username, $userRank);
		
		if (is_array($userData))
		{
			$message = "Beste " . $username . ",<br />
			<br />
			Er is voor jou een HipChat account aangemaakt. HipChat word gebruikt door - als veilig communicatiemiddel tussen de leden. Houd de gegevens van dit account dan ook geheim.<br />
			<br />
			<b>Inlog:</b> " . $userData["email"] . "<br />
			<b>Wachtwoord:</b> " . $userData["password"] . "<br />
			<b>Tagnaam:</b> @" . $userData["mention_name"] . "<br />
			<br />
			Je kan inloggen op <a href=\#\" target=\"_blank\">#</a>. Als je nog vragen hebt, contacteer dan iemand van de directie.<br />
			<br />
			Tot ziens in - !";
			
			$conversaties->newConversation(4, "Je HipChat account gegevens", $username, $message, 1);
			$this->setUserVar("hipchat_user_id", $userData["id"], $username);
			$HipChatAPIRooms->addToPrivateRoom($userData["id"], 1863285);
			
			return "HipChat account aangemaakt.";
		}
		else
		{
			return "fail" . $userData;
		}
	}
	
	public function changeHipChatPassword($username)
	{
		$HipChatAPI    = new HipChatAPIUser();
		$HipChatUserID = $this->getUserVar($username, "hipchat_user_id");
		$conversaties  = new Conversaties();
		$password 	   = $HipChatAPI->changePassword($HipChatUserID);
		
		$message = "Beste " . $username . ",<br />
		<br />
		Je wachtwoord voor je HipChat account is gereset op jouw verzoek. Hieronder staat je nieuwe wachtwoord. Als je dit niet hebt verzocht, meld dit dan direct aan een beheerder.<br />
		<br />
		<b>Je nieuwe wachtword:</b> " . $password . "<br />
		<br />
		Je kan inloggen op <a href=\"#\" target=\"_blank\">#</a>.<br />
		<br />
		Tot ziens in - !";
		
		$conversaties->newConversation(4, "Je HipChat wachtwoord is gereset", $username, $message, 1);
		
		return true;
	}
	
	public function deleteHipChatUser($id)
	{
		$HipChatAPI = new HipChatAPIUser();
		
		$HipChatAPI->deleteUser($id);
	}
	
	public function getLogs()
	{
		$query = sprintf(
			"	SELECT		`paneel_logs`.`id`,
							`paneel_logs`.`habbonaam`,
							`paneel_logs`.`actie`,
							`paneel_logs`.`datum`,
							`paneel_logs`.`ip`
				FROM		`paneel_logs`
				ORDER BY	`paneel_logs`.`id` DESC
				LIMIT 		200"
		);
		$result = $this->getConnection()->query($query);

		$data = array();
		if ($this->getConnection()->num_rows($result) > 0)
		{
			while (
				list(
					$id,
					$habbonaam,
					$actie,
					$datum,
					$ip
				) = $this->getConnection()->fetch_row($result)
			)
			{
				$i = count($data);
				
				$data[$i]['id'] 		= $id;
				$data[$i]['habbonaam'] 	= $habbonaam;
				$data[$i]['actie']	 	= $actie;
				$data[$i]['datum'] 		= $datum;
				$data[$i]['ip'] 		= $ip;
			}
		}
		else
		{
			$data = false;
		}
		
		return $data;
	}
}
?>