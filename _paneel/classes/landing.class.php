<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/user.class.php");

require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/classes/password.class.php");



class Landing extends Object

{

	private $_user;

	private $_password;

	

	public function __construct()

	{

		parent::__construct();

		

		$this->_user = new User();

		$this->_password = new Password();

	}

		

	public function login($username, $password)

	{

		if ($this->_user->userExists($username) === true)

		{

			$storedPassword = $this->_user->getUserVar($username, "wachtwoord");

			$userId 		= $this->_user->getUserVar($username, "id");

			

			if ($this->_password->verifyPassword($password, $storedPassword) === true)

			{	

				$_SESSION['ID'] = $userId;

				$_SESSION['login'] = 1;

				$_SESSION['habbonaam'] = $username;

				

				$this->_user->logAction($userId, "", "succesfull-login");

				

				return true;

			}

			else

			{

				$this->_user->logAction($userId, "", "wrong-login");

			}

		}

		else

		{

			$this->_user->logAction(0, "", "wrong-login");

		}

		

		return false;

	}

	

	public function setUniqueKey()

	{

		$characters  = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';

		$uniqueKey 	 = array();

		$alphaLength = strlen($characters) - 1;

		

		for ($i = 0; $i < 6; $i++)

		{

			$character  = rand(0, $alphaLength);

			$uniqueKey[] = $characters[$character];

		}

		

		$_SESSION["uniqueKey"] = implode($uniqueKey);

	}

	

	public function getUniqueKey()

	{

		return $_SESSION["uniqueKey"];

	}

	

	public function destroyUniqueKey()

	{

		if (isset($_SESSION["uniqueKey"]))

		{

			unset($_SESSION["uniqueKey"]);

		}

	}

	

	public function register($username, $password, $passwordRepeat)

	{

		$errors = array();

		

		if (empty($username))

		{

			$errors[] = "Je hebt geen Habbonaam ingevuld.";

		}

		

		if ($this->_user->userExists($username) === true)

		{

			$errors[] = "Deze Habbo is al geregistreerd.";

		}

		

		if (strpos($username, "#") !== false)

		{

			$errors[] = "Deze naam bevat verboden karakters.";

		}

		

		$passwordCheck = $this->_password->validatePassword($password, $passwordRepeat);

		

		if ($passwordCheck !== true)

		{

			$errors = array_merge($errors, $passwordCheck);

		}

		

		if ($this->_user->checkMotto($username, $this->getUniqueKey()) === false)

		{

			$errors[] = "Je Habbo-motto is niet hetzelfde als je unieke code.";

		}

		

		if (count($errors) == 0)

		{

			$hashedPassword = $this->_password->hashPassword($password);

			

			$this->_user->createUser($username, $hashedPassword);

			

			$newUserId = $this->_user->getUserVar($username, "id");

			

			$this->_user->logAction($newUserId, "", "registered");

			

			return true;

		}

		

		return $errors;

	}

	

	public function passwordReset($username, $password, $passwordRepeat)

	{

		$errors = array();

		

		if (empty($username))

		{

			$errors[] = "Je hebt geen Habbonaam ingevuld.";

		}

		

		if ($this->_user->userExists($username) !== true)

		{

			$errors[] = "Deze Habbo is niet bij ons geregistreerd.";

		}

		

		if ($this->_user->getUserVar($username, "allow_pw_change") == 0)

		{

			$errors[] = "Je hebt nog geen toestemming gevraagd om je wachtwoord te veranderen.";

		}

		

		$passwordCheck = $this->_password->validatePassword($password, $passwordRepeat);

		

		if ($passwordCheck !== true)

		{

			$errors = array_merge($errors, $passwordCheck);

		}

		

		if ($this->_user->checkMotto($username, $this->getUniqueKey()) === false)

		{

			$errors[] = "Je Habbo-motto is niet hetzelfde als je unieke code.";

		}

		

		if (count($errors) == 0)

		{

			$hashedPassword = $this->_password->hashPassword($password);

			$userId 		= $this->_user->getUserVar($username, "id");

			

			$this->_user->setUserVar("wachtwoord", $hashedPassword, $username);

			$this->_user->setUserVar("allow_pw_change", 0, $username);

			$this->_user->logAction($userId, "", "user-pass-reset-success");

			

			return true;

		}

		

		$this->_user->logAction(0, "", "user-pass-reset-wrong", $username);

		

		return $errors;

	}

}