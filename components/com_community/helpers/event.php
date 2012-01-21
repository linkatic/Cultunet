<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CEventHelper
{
	
	/**
	 * Return true if the event is going on today
	 * An event is considered a 'today' event IF
	 * - starting date is today
	 * or
	 * - starting date if in the past but ending date is in the future	 	 	 	 
	 */	 	
	static public function isToday($event)
	{
		$startDate = CTimeHelper::getDate($event->startdate);
		$endDate = CTimeHelper::getDate($event->enddate);
		$now = CTimeHelper::getDate();
		
		// Same year, same day of the year
		$isToday = (
				($startDate->toFormat('%Y') == $now->toFormat('%Y')) 
			&& 	($startDate->toFormat('%j') == $now->toFormat('%j')));
		
		// If still not today, see if the event is ongoing now
		if(!$isToday)
		{
			$nowUnix = $now->toUnix();
			$isToday = (
					($startDate->toUnix() < $nowUnix)
				&&	($endDate->toUnix() > $nowUnix));
		}
		
		return $isToday;
	}
	
	
	/**
	 * Return true if the event is going on this week
	 */	 	
	static public function isThisWeek($event)
	{
	}
	
	static public function formatStartDate($event, $format)
	{
		$startDate = CTimeHelper::getDate($event->startdate);
		$html = $startDate->toFormat($format);
		return $html;
	}
}