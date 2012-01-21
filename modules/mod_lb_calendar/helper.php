<?php
/**
 * LyftenBloggie Tag Cloud Module 1.0.2
 * @package LyftenBloggie 1.0.2
 * @copyright (C) 2009 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class modBloggieCalendarHelper
{
	function getList(&$params, $module)
	{
		global $mainframe;

		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php')) return;
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		// get variables
		$jconfig 		= new JConfig();
		$db 			=& JFactory::getDBO();
		$my 			= &JFactory::getUser();

		$cal_bar        = $params->get( 'cal_bar', '#A2ADBC');
		$cal_subbar     = $params->get( 'cal_subbar', '#E7E7E7');

		$firstdayofweek = intval( $params->get( 'firstdayofweek', 0 ) );
		$mn 			= intval (JArrayHelper::getValue( $_REQUEST, 'mn', 0 ));
		$document 		= & JFactory::getDocument();

		//add header files
		$document->addStyleSheet(JURI::base().'modules/mod_lb_calendar/style.css');

		// Start making the calendar
		$time 			= time();
		$mycurmonth 	= (date( 'm', $time)-1 + $mn)%12 + 1;
		$month 			= date( 'm', $time) + $mn;
		$year 			= date( 'Y', $time) ;
		$yearoff 		= floor((date( 'm', $time)-1 + $mn)/12);
		$year 			+= $yearoff;
		$gid			= (int)$my->get('gid');

		while($mycurmonth <= 0) {
			$mycurmonth += 12;
		}
			 
		$query = "SELECT DAYOFMONTH(e.created) AS created_day, e.created,"
			. " e.id, e.sectionid, YEAR(e.created) AS created_year, MONTH(e.created) AS created_month"
			. " FROM #__bloggies_entries AS e"
			. " WHERE (e.state = 1 )"
			. " AND MONTH(e.created) = '$month'"
			. " AND YEAR(e.created) = '$year'"
			. " AND e.access <= " .(!$gid ? 1 : $gid)
			. " GROUP BY created_year DESC, created_month DESC, created_day DESC";
		$db->setQuery($query);
		$entries = $db->loadObjectList();

		$days = array();
	  
		foreach ( $entries as $entry ) {
			if (($year == $entry->created_year) && ($mycurmonth == $entry->created_month)) {

				$tmptime = $entry->created;

				if ( $tmptime && ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})", $tmptime, $regs ) ) {
					$tmptime 	= mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
					$tmptime 	= $tmptime + ($jconfig->offset*60*60) ;
				}
							
				$day 	= array (date ( 'j', $tmptime ) => array(JRoute::_(LyftenBloggieHelperRoute::getArchiveRoute(JHTML::date( $entry->created, '%Y'), JHTML::date( $entry->created, '%m'), JHTML::date( $entry->created, '%d'), $mn), false)));
				$days 	= $days + $day;
			}
		}
			 
		// Getting Month Names
		$first_of_month = gmmktime(0,0,0,$month-1,1,$year);
		list($tmp, $year, $prev_month, $weekday) = explode(',',gmstrftime('%m,%Y,%b,%w',$first_of_month));
		$first_of_month = gmmktime(0,0,0,$month+1,1,$year);
		list($tmp, $year, $next_month, $weekday) = explode(',',gmstrftime('%m,%Y,%b,%w',$first_of_month));

		$year = date('Y', $time);
		$day_name_length = 1;

		$first_of_month = gmmktime(0,0,0,$month,1,$year);

		$day_names = array();

		for($n=0,$t=(3+$firstdayofweek)*86400; $n<7; $n++,$t+=86400)
		$day_names[$n] = ucfirst(gmstrftime('%A',$t));

		list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
			
		$weekday 			= ($weekday + 7 - $firstdayofweek) % 7;
		$abbr_month_name 	= $params->get( 'monthname_width', 0 )?mb_substr($month_name,0,3):$month_name;
		$abbr_year 			= $params->get( 'year_width', 1 )?$year:mb_substr($year, 2, 3);
			
		if ($params->get( 'monthyearformat', 0 )==1)
			$title   = $abbr_year.'&nbsp;'.htmlentities(ucfirst($month_name), ENT_QUOTES, 'UTF-8');  
		else
			$title   = htmlentities(ucfirst($month_name), ENT_QUOTES, 'UTF-8').'&nbsp;'.$abbr_year;  #note that some locales don't capitalize month and day names

		// Begin calendar
		$calendar 	= '<style type="text/css">
table#calendar caption {background: '.$cal_bar.';}
table#calendar .today, table#calendar td.today a, table#calendar td.today a:link, table#calendar td.today a:visited {background: '.$cal_bar.';}
able#calendar{border-left: 1px solid '.$cal_subbar.';}
table#calendar th {background: '.$cal_subbar.';border-right: 1px solid '.$cal_subbar.';border-bottom: 1px solid '.$cal_subbar.';border-top: 1px solid '.$cal_subbar.';}
table#calendar td {border-right: 1px solid '.$cal_subbar.';border-bottom: 1px solid '.$cal_subbar.';}
</style>';

		$calendar 	.= '<table id="calendar" cellspacing="0" cellpadding="0">'
		.'<caption><a href="'.JRoute::_( 'index.php?option=com_lyftenbloggie&view=lyftenbloggie&mn='.($mn - 1) ).'" title="'.JText::_('PREVIOUS MONTH').'" rel="nofollow" class="navl">&laquo;</a><a href="'.JRoute::_( 'index.php?option=com_lyftenbloggie&view=lyftenbloggie&mn='.($mn + 1) ).'" title="'.JText::_('NEXT MONTH').'" rel="nofollow" class="navr">&raquo;</a> <div class="month-title">'.$title.'</div></caption><tr>';

		if($day_name_length){
			foreach($day_names as $d) {
				$day_name = htmlentities($d, ENT_QUOTES, 'UTF-8');
				$calendar .= '<th scope="col" abbr="'.$day_name.'" title="'.$day_name.'">'.htmlentities($day_name_length < 4 ? mb_substr($d,0,$day_name_length) : $d, ENT_QUOTES, 'UTF-8').'</th>';
			}
			$calendar .= '</tr>';
		}

		// Today 
		$today 		= date( 'j', $time);
		$currmonth 	= date( 'm', $time);
		$curryear 	= date( 'Y', $time);

		if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
		for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
			if($weekday == 7){
				$weekday   = 0;
				$calendar .= "</tr>\n<tr>";
			}

			$istoday = (($day == $today) & ($currmonth == $month) & ($curryear == $year))?' class="today"':'';
			$space = ($day < 10)?'&nbsp;&nbsp;':'';

			if(isset($days[$day]) and is_array($days[$day])){
				@list($link, $classes, $content) = $days[$day];
				if(is_null($content))  $content  = $day;
				$content = ($link?'<a rel="nofollow" href="'.htmlspecialchars($link, ENT_QUOTES, 'UTF-8').'">':'').$day.($link?'</a>':'');
			}else{
				$content = $day;
			}
			$calendar .= '<td'.$istoday.'>'.$content.'</td>';
		}
		
		if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>';
		$calendar .= "</tr>\n</table>\n";

		return $calendar;		
	}
}
