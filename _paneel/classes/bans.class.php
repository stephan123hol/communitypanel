<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");



class Bans extends Object

{

	private $_user;

	

	public function __construct($banPage = false)

	{

		parent::__construct();

		

		$this->_user = new User();

		// Only enable this function if you know how this works
		//$this->canAccess($_SERVER["REMOTE_ADDR"]);

		$this->checkBanned();

		

		if ($_SESSION["user"]["banned"] === true && $banPage === false)

		{

			header("Location: /banned");

			exit;

		}

	}

	

	public function canAccess($ip)

	{

		$whitelistHostnames = array(

			"ziggo",

			"telenet",

			"kpn", 

			"onsbrabantnet", 

			"tele2", 

			"xs4all", 

			"zeelandnet", 

			"telfort", 

			"planet", 

			"caiway", 

			"prioritytelecom", 

			"upc", 

			"chello", 

			"belgacom",

			"direct-adsl",

			"online.nl",

			"hetnet.nl",

			"92.110", // IP reeks Ziggo

			"145.133", // IP reeks Ziggo

			"145.100", // IP reeks Ziggo

			"verixi.net",

			"mxposure", // xs4all

			"versatel",

			"introweb.nl",

			"belgacom.be",

			"schedom-europe.net",

			"open.net",

			"glasoperator.nl",

			"62.72.", // IP reeks Vodafone

			"62.140.", // IP reeks Vodafone

			"scarlet.be",

			"145.132.", // IP reeks Telfort (KPN)

			"143.177.", // IP reeks Tele2

			"62.205.", // IP reeks Telenet

			"stipte.nl",

			"proximus.be",

			"egcs.nl",

			"81.4.104.129", // RA4WVPN Alblasserdam IP

			"de-falkenstein.ra4wvpn.com",

			"62.235.", // IP reeks Scarlet (BE)

			"onenet.cw", // Curacao

			"netvisit.nl",

			"hotelschoolhasselt.be",

			"143.176.232", // Tele2

			"143.176.214", // Tele2

			"143.176.233", // Tele2

			"ddfr.nl",

			"dsl.cambrium.nl",

			"lombox",

			"solcon.nl",

		);

		$accessAllowed = false;

		

		foreach($whitelistHostnames as $key => $value)

		{

			if (strpos(gethostbyaddr($ip), $value) !== false)

			{

				$accessAllowed = true;

				break;

			}

		}

		

		if ($accessAllowed === false)

		{

			die("Je hostname is niet toegestaan voor toegang tot het paneel. Indien je denkt dat dit een fout is, geef onderstaande door aan het RvB:<br />" . gethostbyaddr($ip));

		}

	}

	

	public function getBans()

	{

		$query = sprintf(

			"	SELECT		`paneel_bans`.`id`,

							`paneel_bans`.`type`,

							`paneel_bans`.`value`,

							`paneel_bans`.`reason`,

							`paneel_bans`.`by`,

							`paneel_bans`.`added_on`

				FROM		`paneel_bans`

				ORDER BY	`paneel_bans`.`id` DESC"

		);

		$result = $this->getConnection()->query($query);



		$data = array();

		if ($this->getConnection()->num_rows($result) > 0)

		{

			while (

				list(

					$id,

					$type,

					$value,

					$reason,

					$by,

					$added_on

				) = $this->getConnection()->fetch_row($result)

			)

			{

				$i = count($data);

				

				$data[$i]['id'] 		= $id;

				$data[$i]['type'] 		= $type;

				$data[$i]['value']	 	= $value;

				$data[$i]['reason'] 	= $reason;

				$data[$i]['by'] 		= $by;

				$data[$i]['added_on'] 	= $added_on;

			}

		}

		else

		{

			$data = false;

		}

		

		return $data;

	}

	

	public function getBan()

	{

		$query = sprintf(

			"	SELECT		`paneel_bans`.`type`,

							`paneel_bans`.`value`,

							`paneel_bans`.`reason`,

							`paneel_bans`.`by`,

							`paneel_bans`.`added_on`

				FROM		`paneel_bans`

				WHERE 		(`paneel_bans`.`type` = 'username' AND value = '%s')

					OR 		(`paneel_bans`.`type` = 'ip' AND value = '%s')

				ORDER BY	`paneel_bans`.`id` DESC

				LIMIT 		1",

			$_SESSION["habbonaam"],

			$_SERVER["REMOTE_ADDR"]

		);

		$result = $this->getConnection()->query($query);



		$data = array();

		

		while (

			list(

				$type,

				$value,

				$reason,

				$by,

				$added_on

			) = $this->getConnection()->fetch_row($result)

		)

		{

			if ($type == "username")

			{

				$type = "Habbonaam";

			}

			else if ($type == "ip")

			{

				$type = "IP";

			}

			

			$data['type'] 		= $type;

			$data['value']	 	= $value;

			$data['reason'] 	= $reason;

			$data['by'] 		= $by;

			$data['added_on'] 	= $added_on;

		}

		

		return $data;

	}

	

	public function addBan($type, $value, $reason, $by)

	{

		$query = sprintf(

			"	INSERT INTO `paneel_bans` (`type`, `value`, `reason`, `by`, `added_on`)

				VALUES ('%s', '%s', '%s', '%s', NOW())",

			$this->getConnection()->escape($type),

			$this->getConnection()->escape($value),

			$this->getConnection()->escape($reason),

			$this->getConnection()->escape($by)

		);

		$this->getConnection()->query($query);

		

		if ($type == "username")

		{

			$this->_user->logAction($_SESSION['ID'], "", "ban-username", null, $value);

		}

		else if ($type == "ip")

		{

			$this->_user->logAction($_SESSION['ID'], "", "ban-ip", null, $value);

		}

	}

	

	public function checkBanned()

	{

		$query = sprintf(

			"	SELECT		`paneel_bans`.`id`

				FROM		`paneel_bans`

				WHERE 		(`paneel_bans`.`type` = 'username' AND value = '%s')

					OR 		(`paneel_bans`.`type` = 'ip' AND value = '%s')

				ORDER BY	`paneel_bans`.`id` DESC",

			$_SESSION["habbonaam"],

			$_SERVER["REMOTE_ADDR"]

		);

		$result = $this->getConnection()->query($query);



		$data = array();

		if ($this->getConnection()->num_rows($result) > 0)

		{

			$_SESSION["user"]["banned"] = true;

		}

		else

		{

			$_SESSION["user"]["banned"] = false;

		}

	}

}