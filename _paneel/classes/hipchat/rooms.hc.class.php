<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/core.hc.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/object.class.php");

class HipChatAPIRooms extends HipChatAPI
{
	public function getAllRooms()
	{
		$data = $this->getFromAPI("room");
	
		$ids = array();
		foreach ($data["items"] as $key => $value) {
			$ids[$value["id"]] = $value["name"];
		}
		
		return $ids;
	}
	
	public function sendMessageToAllRooms($message)
	{
		$ids = $this->getAllRooms();
		
		foreach ($ids as $id => $name)
		{
			$this->postToAPI($id, $message);
		}
	}
	
	public function sendMessageToSpecificRooms($rooms, $message)
	{
		$data = array(
			"message" => $message
		);
		
		foreach ($rooms as $id)
		{
			$this->postToAPI("room", $data, $id, "POST", "message");
		}
	}
	
	public function notifyRechtenhouders($headquarter, $requester, $reason)
	{
		$data = array(
			"message" => "@here er worden rechtenhouders verzocht in HQ" . $headquarter . "! Zo snel mogelijk iemand komen graag. Dit verzoek komt van " . $requester . " met als reden: " . $reason
		);
		
		switch ($headquarter)
		{
			default:
			case 1:
				$id = 2252679;
				break;
			case 2:
				$id = 2365753;
				break;
		}
		
		$this->postToAPI("room", $data, $id, "POST", "message");
	}
	
	public function addToPrivateRoom($userId, $roomId)
	{
		$this->postToAPI("room", null, $roomId, "PUT", "member", $userId);
	}
}