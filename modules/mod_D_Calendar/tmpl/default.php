<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>
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

$path = JURI::root(); 
JHTML::_('behavior.tooltip');
?>

<?php
$doc =& JFactory::getDocument();

$doc->addScript($path . "modules/mod_D_Calendar/D_Functionality.js");

$doc->addScript($path . "modules/mod_D_Calendar/D_Utilities.js");
$doc->addScript($path . "modules/mod_D_Calendar/D_Today.js");

$doc->addScript($path . "modules/mod_D_Calendar/D_Event.js");
$doc->addScript($path . "modules/mod_D_Calendar/D_Day.js");
$doc->addScript($path . "modules/mod_D_Calendar/D_Week.js");
$doc->addScript($path . "modules/mod_D_Calendar/D_Month.js");
$doc->addScript($path . "modules/mod_D_Calendar/D_Calendar.js");

$doc->addScript($path . "modules/mod_D_Calendar/D_API_Methods.js");
$doc->addScript($path . "modules/mod_D_Calendar/D_API_Events.js");

$doc->addStyleDeclaration($D_Calendar_Style);


foreach($eventos as $e)
{
	//echo $e->link_name;
}
 ?>
<div id="outer">
		<div id='calendar'></div>
		
</div>
		
		<script  type='text/javascript' language='javascript'>
			//alert(<?php echo $D_Calendar_Mode; ?>);
			
			window.addEvent('domready', function(){ d_config = new D_Config(); d_config.date_format = 'dmy'; d_config.baseURL = '<?php echo $path; ?>'; d_config.tooltip_position = '16'; });
		
			window.addEvent('domready', function(){ createCalendar('<?php echo $D_Calendar_Mode; ?>', '<?php echo $D_Calendar_AltLocation; ?>', '<?php echo $D_Calendar_Tooltips; ?>'); });
		
			window.addEvent('domready', function(){ loadCalendar(); });
			
			
			window.addEvent('domready', 
				function(){ 
					<?php
						foreach($eventos as $e)
						{
							$titulo = $e->link_name;
							$enlace = "index.php?option=com_mtree&task=viewlink&link_id=".$e->link_id."&Itemid=2";
							$fecha = $e->value;
							
							$fecha = str_replace("/","-",$fecha);
							$fecha_array = explode("-",$fecha);
							$dia= $fecha_array[2];
							$mes = $fecha_array[1];
							$anyo = $fecha_array[0];
	
							
							echo 'D_Calendar_New_Event('.$anyo.', '.$mes.', '.$dia.', '.$anyo.', '.$mes.', '.$dia.', 0, 0, \'<img src="http://www.d-extensions.com/favicon.ico" width="9"  style="padding-top:2px;padding-right:0px;"/><img src="http://www.d-extensions.com/modules/mod_D_Articles/images/arrow.png" width="9"  style="padding-top:2px;padding-right:0px;"/> <a href="'.$path.$enlace.'">'.htmlspecialchars(str_replace("'", " ",$titulo)).'</a>\', \'\');';
							
						}
					?> 
					}
			);
			window.addEvent('domready', function(){ reinitializeCells(); prepareDays(); D_Print(d_calendar.html()); markToday(); loadTooltips();  });
			

			
		</script>
		