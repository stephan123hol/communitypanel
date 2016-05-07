<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

function objectToArray($d)
{
	if (is_object($d))
	{
		$d = get_object_vars($d);
	}

	if (is_array($d))
	{
		return array_map(__FUNCTION__, $d);
	}
	else
	{
		return $d;
	}
}

class HipChatAPI
{
	private $authKey = "";
	
	public function getFromAPI($type, $id = null)
	{		
		if (is_null($id))
		{
			$url = "https://api.hipchat.com/v2/" . $type . "?auth_token=" . $this->authKey;
		}
		else
		{
			$url = "https://api.hipchat.com/v2/" . $type . "/" . $id . "?auth_token=" . $this->authKey;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = objectToArray(json_decode(curl_exec($ch)));
		curl_close($ch);
		
		return $data;
	}
	
	public function postToAPI($type, $data, $id = null, $request = "POST", $action = null, $extra = null)
	{
		if (is_null($id))
		{
			$url = "https://api.hipchat.com/v2/" . $type . "?auth_token=" . $this->authKey;
		}
		else
		{
			if (is_null($action))
			{
				$url = "https://api.hipchat.com/v2/" . $type . "/" . $id . "?auth_token=" . $this->authKey;
			}
			else
			{
				if (is_null($extra))
				{
					$url = "https://api.hipchat.com/v2/" . $type . "/" . $id . "/" . $action . "?auth_token=" . $this->authKey;
				}
				else
				{
					$url = "https://api.hipchat.com/v2/" . $type . "/" . $id . "/" . $action . "/" . $extra . "?auth_token=" . $this->authKey;
				}
			}
		}
		
		$post 	 = json_encode($data);
		$ch   	 = curl_init($url);
		$headers = array('Accept: application/json','Content-Type: application/json'); 

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$result = objectToArray(json_decode(curl_exec($ch)));
		curl_close($ch);

		return $result;
	}
}