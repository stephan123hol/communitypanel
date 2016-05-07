<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/core.hc.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

class HipChatAPIUser extends HipChatAPI
{
	public function createUser($username, $rank, $number = 0)
	{
		$mentionUsername = preg_replace("/[^A-Za-z0-9]/", '', $username);
		$name = str_replace("=", "", $username);
		$random = substr(str_shuffle("0123456789"), 0, 3);
		
		$data = array(
			"name" 			 => $name,
			"title" 		 => "Imperialist",
			"mention_name" 	 => $mentionUsername,
			"is_group_admin" => false,
			"timezone" 		 => "UTC",
			"email"			 => $mentionUsername . $random . "@imperialmaffia.nl"
		);
		
		$response = $this->postToAPI("user", $data);
		
		if(isset($response["error"]))
		{
			if ($response["error"]["message"] == "The mention name is already in use")
			{
				$number++;
				$mentionUsername = $mentionUsername . $number;
				
				$this->createUser($username, $rank, $number);
			}
		}
		else
		{
			$responseData = array(
				"id" 		   => $response["id"],
				"mention_name" => $response["entity"]["mention_name"],
				"username"	   => $username,
				"password" 	   => $response["password"],
				"email" 	   => $mentionUsername . $random . "@imperialmaffia.nl"
			);
			
			return $responseData;
		}
	}
	
	public function deleteUser($id)
	{
		$this->postToAPI("user", null, $id, "DELETE");
	}
	
	public function changePassword($id)
	{
		$chars 	  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr(str_shuffle($chars), 0, 9);
		$oldData  = $this->getData($id);
		
		$newData  = array(
			"name" => $oldData["name"],
			"title" => $oldData["title"],
			"mention_name" => $oldData["mention_name"],
			"is_group_admin" => $oldData["is_group_admin"],
			"timezone" => $oldData["timezone"],
			"password" => $password,
			"email" => $oldData["email"]
		);
		
		$this->postToAPI("user", $newData, $id, "PUT");
		
		return $password;
	}
	
	public function getData($id)
	{
		$response = $this->getFromAPI("user", $id);
		
		return $response;
	}
}