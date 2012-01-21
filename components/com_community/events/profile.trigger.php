<?php
/**
 * @category	Events
 * @package		JomSocial
 * @copyright (C) 2010 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CProfileTrigger
{
	public function onAfterProfileUpdate($userid, $save)
	{
		if($save)
		{
			// Update user's IP location
			$usermodel	=CFactory::getModel('user');
			$user  = CFactory::getUser($userid);
			$juser =& JFactory::getUser($userid);
			
			CFactory::load('libraries', 'mapping');
			 
			// Build the address string 
			$address = $user->getAddress();
			
			// Store the location
			$data = CMapping::getAddressData($address);
			
			// reset it to null;
			$latitude 	= COMMUNITY_LOCATION_NULL;
			$longitude	= COMMUNITY_LOCATION_NULL;
			
			if($data){
				if($data->status == 'OK')
				{
					$latitude  = $data->results[0]->geometry->location->lat;
					$longitude = $data->results[0]->geometry->location->lng; 
				}
			}
			
			$usermodel->storeLocation($user->id, $latitude, $longitude);
			
			
			// Update user's firstname and lastname. Only update if both is not 
			// empty and is actually specifies
			$givenName  = $user->getInfo('FIELD_GIVENNAME');
			$familyName = $user->getInfo('FIELD_FAMILYNAME');
			
			if(!empty($givenName) && !empty($familyName))
			{
				
				$juser->name = $givenName . ' ' .$familyName;
				
				// We need to update the cuser object too since it is static,
				// it might still be used
				$user->name = $juser->name;
				
				if(!$juser->save()){
					// save failed ?
				}
			}
			
			
			// Update all user counts
			$friendModel = CFactory::getModel('friends');
			$friendModel->updateFriendCount($userid);
			//$user->_friendcount = $numFriend;
			//echo $user->save();
			//echo $user->_friendcount;
		} 
	}
	
	/**
	 * Method is called during the status update triggers. 
	 **/
	public function onProfileStatusUpdate( $userid , $oldMessage , $newMessage )
	{
		$config	=& CFactory::getConfig();
		
		if( $config->get('fbconnectpoststatus') )
		{
			CFactory::load( 'libraries' , 'facebook' );
			$facebook	= new CFacebook();
			
			if( $facebook )
			{
				$facebook->postStatus( $newMessage );
			}
		}
	}
}