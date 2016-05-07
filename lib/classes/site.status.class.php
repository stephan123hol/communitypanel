<?php

class SiteStatus
{
	/**
	* @param    $maintenanceMode		bool		The switch. Is there maintenance yes or no.
	* @param    $maintananceGlobal		bool		Is the maintenance everywhere? If so, set to true, otherwise false.
	* @param    $maintenanceLocations	array		If $maintananceGlobal is set to false, please fill in the locations of the maintenance (panelify, portal, main);
	* @param	$maintenanceMessage		string		The message to show on the activated maintenance pages.
	*/
	
	private $maintenanceMode      = false;
	private $maintananceGlobal    = true;
	private $maintenanceLocations = array("panelify");
	private $maintenanceMessage   = 'Coming back soon!'; 
	
	public function inMaintenance($currentLocation)
	{
		if ($this->maintenanceMode === true)
		{
			if ($this->canAccess() === false)
			{
				if ($this->maintananceGlobal === false)
				{
					if (in_array($currentLocation, $this->maintenanceLocations))
					{
						die($this->maintenanceMessage);
					}
				}
				else
				{
					die($this->maintenanceMessage);
				}
			}
		}
	}
	
	public function canAccess()
	{
		$maintenanceAdmins = array('ip');
								   
		if (in_array($_SERVER['REMOTE_ADDR'], $maintenanceAdmins))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>
