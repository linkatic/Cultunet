<?php
/**
 * @category	Library
 * @package		JomSocial
 * @subpackage	user 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CKarma {
	
	/**
	 * return the path to karma image
	 * @param	user	CUser object	 
	 */	 	
	function getKarmaImage( $user ) {
		jimport('joomla.filesystem.file');
		$points = $user->getKarmaPoint();
		
		$config		=& CFactory::getConfig();

		$imageFile = DS.'components'. DS . 'com_community' .DS . 'templates'. DS . $config->get('template') . DS . 'images' . DS;
		$filename = '';

		// If user does not change their profile picture, it should never get past 0.5 points
		if( $user->getThumbAvatar() == (JURI::base() . 'components/com_community/assets/default_thumb.jpg'))
		{
			$filename = 'karma-0-5';
		}
		else if ($points <= $config->get('point0') )
		{
			$filename = 'karma-0.5-5';
		}

		if( $points > $config->get('point1') ) {
			$filename = 'karma-1-5';
		}

		if( $points > $config->get('point2') )
		{
			$filename = 'karma-2-5';
		}

		if( $points > $config->get('point3') )
		{
			$filename = 'karma-3-5';
		}

		if( $points > $config->get('point4') )
		{
			$filename = 'karma-4-5';
		}

		if( $points > $config->get('point5') )
		{
			$filename = 'karma-5-5';
		}

		$imageFile .= $filename;
		
		// If the file doesn't exist, load default template
		// @todo: cache this to avoid too much file exist cheack
		if( JFile::exists(JPATH_ROOT . $imageFile. '.png' ))
		{
			$imageFile .= '.png';
		} 
		elseif( JFile::exists(JPATH_ROOT . $imageFile. '.gif' ))
		{
			$imageFile .= '.gif';
		}
		else
		{
			$imageFile = str_replace( $config->get('template') , 'default' , $imageFile);
			$imageFile .= '.gif';
		}
		
		// Convert this server path to url
		$imageFile = str_replace( DS , '/', $imageFile);
		return JURI::base() . $imageFile;
	}
	
	
	/**
	 * add points to user based on the action.
	 */	 	
	function assignPoint( $action, $userId=null)
	{
		//get the rule points
		$user	= CFactory::getUser($userId);
		$points	= CKarma::getActionPoint($action, $user->gid);
					
		$points	+= $user->getKarmaPoint();
		
		$user->_points = $points;
		$user->save();
	}
	
	
	/**
	 * Return points for various actions. Return value should be configurable from the backend
	 * 	 
	 */	 	
	function getActionPoint( $action, $gid = 0) {
	
		include_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'models'.DS.'userpoints.php');
		
		$userPoint = '';
		if( class_exists('CFactory') ){
			$userPoint =& CFactory::getModel('userpoints');
		} else {
			$userPoint = new CommunityModelUserPoints();
		}
		
		$point	= 0;
		$upObj	= $userPoint->getPointData( $action );
		
		if(! empty($upObj))
		{
			$published	= $upObj->published;			
			$point		= $upObj->points;
			$access		= $upObj->access;
			
			if ($published == '0')
				$point = 0;
			else if($access != $gid)
				$point = 0;

		}			
							
		
		return $point;
	}
	
	
}