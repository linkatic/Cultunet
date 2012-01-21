<?php
/**
Copyright 2010 - Kastaniotis Dimitris - D-Extensions.com
license GNU/GPL http://www.gnu.org/copyleft/gpl.html


This file is part of D Calendar.

D Calendar is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

D Calendar is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with D Calendar.  If not, see <http://www.gnu.org/licenses/>.

**/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
class Mod_D_Calendar
{
    /**
     * Returns the Title of the module
    */
    public function getTitle()
    {
        return "D Calendar Module";
    } 
	
	public function getEvents($cat_id = 85)
	{
		//$cat_id = JRequest::getVar('cat_id');
		
		$db =& JFactory::getDBO();
		
		$hoy = date("Y-m-d");
		
		//Eventos de la categoria "agenda" (85), sacamos solamente la fecha de inicio 
		$query = "SELECT * FROM #__mt_cl AS cl 
		INNER JOIN #__mt_links AS lk 
		INNER JOIN #__mt_cfvalues AS cfv 
		ON cfv.cf_id=33 AND cfv.link_id = cl.link_id AND lk.link_id = cl.link_id AND cfv.value >= '".$hoy."' AND cat_id = '".$cat_id."'";

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;

	}
 
}
?>