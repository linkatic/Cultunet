<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CTimeHelper
{
	static public function timeDifference( $start , $end )
	{
		jimport('joomla.utilities.date');
		
		if(is_string($start) && ($start != intval($start))){
			$start = new JDate($start);
			$start = $start->toUnix();
		}
		
		if(is_string($end) && ($end != intval($end) )){
			$end = new JDate($end);
			$end = $end->toUnix();
		}
			
	    $uts['start']      =    $start ;
	    $uts['end']        =    $end ;
	    if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
	        if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        else
	        {
	            trigger_error( JText::_("CC TIME IS EARLIER THAN START"), E_USER_WARNING );
	        }
	    }
	    else
	    {
	        trigger_error( JText::_("CC INVALID DATETIME"), E_USER_WARNING );
	    }
	    return( false );
	}
	
	static public function timeIntervalDifference( $start , $end )
	{
		jimport('joomla.utilities.date');
		
		
		$start = new JDate($start);
		$start = $start->toUnix();
		
		$end = new JDate($end);
		$end = $end->toUnix();
	
			
	    if( $start !==-1 && $end !==-1 )
	    {
			return ($start - $end);
	    }
	    else
	    {
	        trigger_error( JText::_("CC INVALID DATETIME"), E_USER_WARNING );
	    }
	    return( false );
	}
	
	static public function formatTime( $jdate )
	{
		jimport('joomla.utilities.date');
		return JString::strtolower($jdate->toFormat('%I:%M %p'));
	}
	
	static public function getInputDate( $str = '' )
	{
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
	        
		$mainframe	=& JFactory::getApplication();
		$config		=& CFactory::getConfig();	
		
		$timeZoneOffset = $mainframe->getCfg('offset');
		$dstOffset		= $config->get('daylightsavingoffset');
		
		$date	= new JDate($str);
		$my		=& JFactory::getUser();
		$cMy	= CFactory::getUser();
		
		if($my->id)
		{
			if(!empty($my->params))
			{
				$timeZoneOffset = $my->getParam('timezone', $timeZoneOffset);
	
				$myParams	= $cMy->getParams();
				$dstOffset	= $myParams->get('daylightsavingoffset', $dstOffset);
			} 
		}
		
		$timeZoneOffset = (-1) * $timeZoneOffset; 
		$dstOffset		= (-1) * $dstOffset;
		$date->setOffset($timeZoneOffset + $dstOffset);
		
		return $date;
	}
	
	static public function getDate( $str = '' )
	{
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
	        
		$mainframe	=& JFactory::getApplication();
		$config		=& CFactory::getConfig();	
		
		
		$extraOffset	= $config->get('daylightsavingoffset');
		
		$date	= new JDate($str);
		$my		=& JFactory::getUser();
		$cMy	= CFactory::getUser();
		
		if(!$my->id){
			$date->setOffset($mainframe->getCfg('offset') + $extraOffset);
		} else{
			if(!empty($my->params)){
				$pos = JString::strpos($my->params, 'timezone');
				
				$offset = $mainframe->getCfg('offset') + $extraOffset;
				if ($pos === false) {
				   $offset = $mainframe->getCfg('offset') + $extraOffset;
				} else {
					$offset 	= $my->getParam('timezone', -100);
				   
					$myParams	= $cMy->getParams();
					$myDTS		= $myParams->get('daylightsavingoffset');			   		
					$cOffset	= (! empty($myDTS)) ? $myDTS : $config->get('daylightsavingoffset');			   
				   
					if($offset == -100)
						$offset = $mainframe->getCfg('offset') + $extraOffset;
					else
						$offset = $offset + $cOffset;	
				}
				$date->setOffset($offset);
			} else
				$date->setOffset($mainframe->getCfg('offset') + $extraOffset);
		}
		
		return $date;
	}
	
	static public function getTimezone( $offset )
	{
		$timezeon= array();
		$timezone['-11'] = JText::_('(UTC -11:00) Midway Island, Samoa');
		$timezone['-10'] = JText::_('(UTC -10:00) Hawaii');
		$timezone['-9.5'] = JText::_('(UTC -09:30) Taiohae, Marquesas Islands');
		$timezone['-9'] = JText::_('(UTC -09:00) Alaska');
		$timezone['-8'] = JText::_('(UTC -08:00) Pacific Time (US &amp; Canada)');
		$timezone['-7'] = JText::_('(UTC -07:00) Mountain Time (US &amp; Canada)');
		$timezone['-6'] = JText::_('(UTC -06:00) Central Time (US &amp; Canada), Mexico City');
		$timezone['-5'] = JText::_('(UTC -05:00) Eastern Time (US &amp; Canada), Bogota, Lima');
		$timezone['-4'] = JText::_('(UTC -04:00) Atlantic Time (Canada), Caracas, La Paz');
		$timezone['-4.5'] = JText::_('(UTC -04:30) Venezuela');
		$timezone['-3.5'] = JText::_('(UTC -03:30) St. John\'s, Newfoundland, Labrador');
		$timezone['-3'] = JText::_('(UTC -03:00) Brazil, Buenos Aires, Georgetown');
		$timezone['-2'] = JText::_('(UTC -02:00) Mid-Atlantic');
		$timezone['-1'] = JText::_('(UTC -01:00) Azores, Cape Verde Islands');
		$timezone['0'] = JText::_('(UTC 00:00) Western Europe Time, London, Lisbon, Casablanca');
		$timezone['1'] = JText::_('(UTC +01:00) Amsterdam, Berlin, Brussels, Copenhagen, Madrid, Paris');
		$timezone['2'] = JText::_('(UTC +02:00) Istanbul, Jerusalem, Kaliningrad, South Africa');
		$timezone['3'] = JText::_('(UTC +03:00) Baghdad, Riyadh, Moscow, St. Petersburg');
		$timezone['3.5'] = JText::_('(UTC +03:30) Tehran');
		$timezone['4'] = JText::_('(UTC +04:00) Abu Dhabi, Muscat, Baku, Tbilisi');
		$timezone['4.5'] = JText::_('(UTC +04:30) Kabul');
		$timezone['5'] = JText::_('(UTC +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent');
		$timezone['5.5'] = JText::_('(UTC +05:30) Bombay, Calcutta, Madras, New Delhi, Colombo');
		$timezone['5.75'] = JText::_('(UTC +05:45) Kathmandu');
		$timezone['6'] = JText::_('(UTC +06:00) Almaty, Dhaka');
		$timezone['6.30'] = JText::_('(UTC +06:30) Yagoon');
		$timezone['7'] = JText::_('(UTC +07:00) Bangkok, Hanoi, Jakarta');
		$timezone['8'] = JText::_('(UTC +08:00) Beijing, Perth, Singapore, Hong Kong');
		$timezone['8.75'] = JText::_('(UTC +08:00) Ulaanbaatar, Western Australia');
		$timezone['9'] = JText::_('(UTC +09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk');
		$timezone['9.5'] = JText::_('(UTC +09:30) Adelaide, Darwin, Yakutsk');
		$timezone['10'] = JText::_('(UTC +10:00) Eastern Australia, Guam, Vladivostok');
		$timezone['10.5'] = JText::_('(UTC +10:30) Lord Howe Island (Australia)');
		$timezone['11'] = JText::_('(UTC +11:00) Magadan, Solomon Islands, New Caledonia');
		$timezone['11.30'] = JText::_('(UTC +11:30) Norfolk Island');
		$timezone['12'] = JText::_('(UTC +12:00) Auckland, Wellington, Fiji, Kamchatka');
		$timezone['12.75'] = JText::_('(UTC +12:45) Chatham Island');
		$timezone['13'] = JText::_('(UTC +13:00) Tonga');
		$timezone['14'] = JText::_('(UTC +14:00) Kiribati');
		
		return $timezone[$offset];
	}
}

/**
 * Deprecated since 1.8
 * Use CTimeHelper::timeDifference instead. 
 */
function cTimeDifference( $start, $end )
{
	return CTimeHelper::timeDifference( $start , $end );
}

/**
 * Deprecated since 1.8
 * Use CTimeHelper::timeIntervalDifference instead. 
 */
function cTimeIntervalDiff( $start, $end )
{
	return CTimeHelper::timeIntervalDifference( $start , $end );
}

/**
 * Deprecated since 1.8
 * Use CTimeHelper::formatTime instead. 
 */
function cFormatTime( $jdate )
{
	return CTimeHelper::formatTime( $date );
}

/**
 * Deprecated since 1.8
 * Use CTimeHelper::getInputDate instead. 
 */
function cGetInputDate($str = '')
{
	return CTimeHelper::getInputDate( $str );
}

/**
 * Deprecated since 1.8
 * Use CTimeHelper::getDate instead. 
 */
function cGetDate($str = '')
{
	return CTimeHelper::getDate( $str );
}

/**
 * Deprecated since 1.8
 * Use CTimeHelper::getTimezone instead. 
 */
function cTimezoneIdentifier($offset)
{
	return CTimeHelper::getTimezone( $offset );
}
