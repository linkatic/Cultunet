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

//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
 
// include the helper file
require_once(dirname(__FILE__).DS.'helper.php');
 
// get the items to display from the helper
$items = Mod_D_Calendar::getTitle();

// get events
$eventos = Mod_D_Calendar::getEvents();

$hola = "hola";

//$D_Calendar_Header = $params->get('D_Calendar_Header');
//$D_Calendar_Footer = $params->get('D_Calendar_Footer');
$D_Calendar_Style = $params->get('D_Calendar_Style');
$D_Calendar_Mode = $params->get('D_Calendar_Mode');
$D_Calendar_AltLocation = $params->get('D_Calendar_AltLocation');
$D_Calendar_Tooltips = $params->get('D_Calendar_Tooltips');

 
// include the template for display
require(JModuleHelper::getLayoutPath('mod_D_Calendar'));
?>