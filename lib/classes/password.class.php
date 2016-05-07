<?php

class Password
{
	public function hashPassword($password)
	{
		$options = [
			'cost' => 9,
		];
		
		$hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
		
		if ($hashedPassword !== false)
		{
			return $hashedPassword;
		}
		else
		{
			return false;
		}
	}
	
	public function verifyPassword($password, $storedPassword)
	{
		if (password_verify($password, $storedPassword))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function validatePassword($password, $passwordCheck)
	{
		$errors = array();
		
		if (empty($password))
		{
			$errors[] = "Je hebt geen wachtwoord ingevuld!";
		}
		
		if (empty($passwordCheck))
		{
			$errors[] = "Je hebt wachtwoord herhalen niet ingevuld!";
		}
		
		if (count($errors) == 0)
		{
			if ($password == $passwordCheck)
			{
				if (preg_match("/.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/", $password))
				{
					return true;
				}
				else
				{
					$errors[] = "Je wachtwoord voldoet niet aan de eisen (minimaal 1 hoofdletter, 1 kleine letter en 1 nummer. Je wachtwoord moet 8-20 karakters lang zijn.)";
					return $errors;
				}
			}
			else
			{
				$errors[] = "De wachtwoorden komen niet overeen.";
				return $errors;
			}
		}
		else
		{
			return $errors;
		}
	}
	
	public function randomPassword($passwordLength)
	{
		$alphabet 	 = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$password 	 = array();
		$alphaLength = strlen($alphabet) - 1;
		
		for ($i = 0; $i < $passwordLength; $i++)
		{
			$character  = rand(0, $alphaLength);
			$password[] = $alphabet[$character];
		}
		
		return implode($password);
	}
}
?>