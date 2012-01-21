<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Profile
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

class CommunityModelRegister extends JCCModel
{
	function setRole($role,$user_id)
	{
		$db	 = &$this->getDBO();
		$date = JFactory::getDate();
		
		$data =new stdClass();
		$data->id = null;
		$data->uid = $user_id;;
		$data->role = $role;
		$data->dated = $date->toMySQL();
		
		$db = JFactory::getDBO();
		$db->insertObject( '#__js_job_userroles', $data, id );
	}
	
	function setJobCompany($info)
	{
		$db	 = &$this->getDBO();
		$date = JFactory::getDate();
		
		$data =new stdClass();
		$data->id = null;
		$data->uid = $info['uid'];
		$data->category = $info['category'];
		$data->name = $info['name'];
		$data->contactname = $info['contactname'];
		$data->contactemail = $info['email'];
		$data->created = $date->toMySQL();
		
		//print_r($data);
		
		$db->insertObject( '#__js_job_companies', $data, id );
	}

	/*
	 * adding temporary user details
	 */ 			 
    function addTempUser($data) {
	    $db    =& $this->getDBO();
	    
		//get current session id.		
		$mySess 	=& JFactory::getSession();
		$token		= $mySess->get('JS_REG_TOKEN','');
		
		$nowDate = JFactory::getDate();
		$nowDate = $nowDate->toMysql();
	    	    
		$obj = new stdClass();
		$obj->name			= $data['jsname'];
		$obj->token			= $token;
		$obj->username		= $data['jsusername'];
		$obj->email			= $data['jsemail'];
		$obj->password		= $data['jspassword'];
		$obj->created		= $nowDate;
		$obj->ip			= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		
		$db->insertObject('#__community_register', $obj);
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return true;
	}

	/*
	 * Get temporary user details based on token string.
	 */		
	function getTempUser($token) {
		$db    =& $this->getDBO();
		
		//the password2 is for JUser binding purpose.
		
		$query = "SELECT *, `password` as `password2`  FROM ".$db->nameQuote('#__community_register');
		$query .= " WHERE `token` = ".$db->Quote($token);
		$db->setQuery($query);
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		$result = $db->loadObject();
		return $result;
	}

	/*
	 * remove the temporary user from register table.
	 */		
	function removeTempUser($token){
		$db    =& $this->getDBO();
		
		$query = "DELETE FROM ".$db->nameQuote('#__community_register');
		$query .= " WHERE `token` = ".$db->Quote($token);
		
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function cleanTempUser(){
		$nowDate		= JFactory::getDate();
		$nowDateMysql	= $nowDate->toMySQL();
		
		$db    =& $this->getDBO();
		
		$query = "DELETE FROM ".$db->nameQuote('#__community_register');
		$query .= " WHERE `created` <= DATE_SUB('".$nowDateMysql."',  INTERVAL 15 MINUTE)";		
		
		$db->setQuery($query);
		$db->query();
		
		//
		$query = "DELETE FROM ".$db->nameQuote('#__community_register_auth_token');
		$query .= " WHERE `created` <= DATE_SUB('".$nowDateMysql."',  INTERVAL 10 MINUTE)";
		
		$db->setQuery($query);
		$db->query();				
		
	}


	/**
	 * Adding user extra custom profile
	 */	 	
	function addCustomProfile($data){	    
		    
		$db    =& $this->getDBO();
		
		$ok   = false;
		$user = $data['user'];
		$post = $data['post'];
		
		$query = "SELECT * FROM " . $db->nameQuote('#__community_fields') 
			. " WHERE `published`='1' "
			. " AND `type` != 'group'"
			. " ORDER BY `ordering` ";
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		
// 		echo "<pre>";
// 		print_r($post);
// 		echo "</pre>";
// 		echo "<br/>";		
		
		// Bind result from previous post into the field object
		if(! empty($post)){
			for($i = 0; $i <count($fields); $i++){
				$fieldid = $fields[$i]->id;
				
// 				echo "<pre>";
// 				print_r($post['field'.$fieldid]);
// 				echo "</pre>";
// 				echo "<br/>";
				
				if(! empty($post['field'.$fieldid])){
					$fields[$i]->value = $post['field'.$fieldid];
				} else {
				    $fields[$i]->value = '';
				}
			}
			
			foreach ($fields as $field){
				$rcd = new stdClass();
				$rcd->user_id  = $user->id;
				$rcd->field_id = $field->id;
				
				if(is_array($field->value)){
				    $tmp	= '';
				
					// Now we need to test for 'date' specific fields as we need to convert the value
					// to unix timestamp
					$query	= 'SELECT ' . $db->nameQuote('type') . ' FROM ' . $db->nameQuote('#__community_fields') . ' '
							. 'WHERE ' . $db->nameQuote('id') . '=' . $db->Quote( $field->id );
					$db->setQuery( $query );
					$type	= $db->loadResult();
				
                	if( $type == 'date' )
					{
					    $values = $field->value;
						$day	= intval($values[0]);
						$month	= intval($values[1]);
						$year	= intval($values[2]);
						
						$day 	= !empty($day) 		? $day 		: 1;
						$month 	= !empty($month) 	? $month 	: 1;
	
						$tmp	= gmmktime( 0 , 0 , 0 , $month , $day , $year );						
					} else {
						foreach($field->value as $val)
						{
							$tmp .= $val . ',';
						}//end foreach
					}
					$rcd->value = $tmp;
				} else {				
				    $rcd->value	   = $field->value;
				}//end if
				
				$db->insertObject('#__community_fields_values', $rcd);
			}//end foreach
			
			$ok = true;
		}//end if
	    
	    return $ok;
	}
	
	/*
	 * 
     */
	function isUserNameExists($filter = array()){
		$db			= &$this->getDBO();
		$found		= false;
		
// 		$query = "(SELECT `username`";
// 		$query .= " FROM #__users";
// 		$query .= " WHERE UCASE(`username`) = UCASE(".$db->Quote($filter['username'])."))";
// 		$query .= " UNION ";
// 		$query .= "(SELECT `username`";
// 		$query .= " FROM #__community_register";
// 		$query .= " WHERE UCASE(`username`) = UCASE(".$db->Quote($filter['username'])."))";

		/*
		 * DO NOT USE UNION. It will failed if the user joomla table's collation type was
		 * diferent from jomsocial tables's collation type
		 */		 		 

		$query = "SELECT `username`";
		$query .= " FROM #__users";
		$query .= " WHERE UCASE(`username`) = UCASE(".$db->Quote($filter['username']).")";
		
		$db->setQuery( $query );
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		$result = $db->loadObjectList();
		$found = (count($result) == 0) ? false : true;
		
		if(! $found && isset( $filter['ip'] ) ){
		
			$query = "SELECT `username`";
			$query .= " FROM #__community_register";
			$query .= " WHERE UCASE(`username`) = UCASE(".$db->Quote($filter['username']).")";
			$query .= " AND `ip` != ".$db->Quote($filter['ip']);		
		
			$db->setQuery( $query );
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
			$result = $db->loadObjectList();
			$found = (count($result) == 0) ? false : true;
		}
				
		return $found;
	
	}

	/*
	 * Method to check for exsisting email registered in jomsocial
     */	
	function isEmailExists($filter = array()){
		$db			= &$this->getDBO();
		$found		= false;
		
// 		$query = "(SELECT `email`";
// 		$query .= " FROM #__users";
// 		$query .= " WHERE UCASE(`email`) = UCASE(".$db->Quote($filter['email'])."))";
// 		$query .= " UNION";
// 		$query .= "(SELECT `email`";
// 		$query .= " FROM #__community_register";
// 		$query .= " WHERE UCASE(`email`) = UCASE(".$db->Quote($filter['email'])."))";
		
		$query = "SELECT `email`";
		$query .= " FROM #__users";
		$query .= " WHERE UCASE(`email`) = UCASE(".$db->Quote($filter['email']).")";
		
		$db->setQuery( $query );
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		$result = $db->loadObjectList();
		$found = (count($result) == 0) ? false : true;
		
		if(! $found){
		
			$query = "SELECT `email`";
			$query .= " FROM #__community_register";
			$query .= " WHERE UCASE(`email`) = UCASE(".$db->Quote($filter['email']).")";
			if((isset($filter['ip'])) && (! empty($filter['ip'])))
				$query .= " AND `ip` != ".$db->Quote($filter['ip']);		
		
			$db->setQuery( $query );
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
			$result = $db->loadObjectList();
			$found = (count($result) == 0) ? false : true;
		}
				
		return $found;
	
	}
	
	/**
	 * Function used to add new auth key
	 * param : new auth key - string
	 * return : boolean	 
	 */	
	function addAuthKey ($authKey='')
	{
	    $db    =& $this->getDBO();
	    
		//get current session id.
		$mySess 	=& JFactory::getSession();
		$token		= $mySess->get('JS_REG_TOKEN','');
		
		$nowDate = JFactory::getDate();
		$nowDate = $nowDate->toMysql();
	    	    
		$obj = new stdClass();		
		$obj->token			= $token;
		$obj->auth_key		= $authKey;
		$obj->created		= $nowDate;
		$obj->ip			= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		
		$db->insertObject('#__community_register_auth_token', $obj);
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
				
		return true;		
	}
	
	
	/**
	 * Function used to remove the assigned auth key.
	 *  param : current token - string	 
	 */	 	
	function removeAuthKey ($token='')
	{
		$db    =& $this->getDBO();
		
		$query = "DELETE FROM ".$db->nameQuote('#__community_register_auth_token');
		$query .= " WHERE `token` = ".$db->Quote($token);
		
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}	
	}	
	
	/**
	 * Function used to get the valid auth key
	 * param : current token - string
	 *       : user ip address - string
	 * return : auth key - string      	 	 	 	 
	 */	
	function getAuthKey ($token='', $ip='')
	{
		$authKey		= "";
		$curDate		= JFactory::getDate();
		$curDateMysql	= $curDate->toMySQL();
		
		$db    =& $this->getDBO();
		
		$config			=& CFactory::getConfig();
		$expiryPeriod	= $config->get( 'sessionexpiryperiod' );			
	    $expiryPeriod	= (empty($expiryPeriod)) ? "600" : $expiryPeriod;
		
	    
		$query = "SELECT `auth_key` FROM ".$db->nameQuote('#__community_register_auth_token');
		$query .= " WHERE `created` >= DATE_SUB('".$curDateMysql."', INTERVAL ". $expiryPeriod . " SECOND)";
		$query .= " AND `token` = " . $db->Quote($token);
		$query .= " AND `ip` = " . $db->Quote($ip);
		
		$db->setQuery($query);								
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		$authKey	= $db->loadResult();
		return $authKey;
	}
	
	/**
	 * Function used to get the existing assigned auth key.
	 * param : current token - string
	 *       : user ip address - string
	 * return : auth key - string      	 	 	 	 
	 */	
	function getAssignedAuthKey ($token='', $ip='')
	{
		$authKey		= "";
		$curDate		= JFactory::getDate();
		$curDateMysql	= $curDate->toMySQL();	
	
	    $db    =& $this->getDBO();

		$query = "SELECT `auth_key` FROM ".$db->nameQuote('#__community_register_auth_token');		
		$query .= " WHERE `token` = " . $db->Quote($token);
		$query .= " AND `ip` = " . $db->Quote($ip);
		
		$db->setQuery($query);								
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		$authKey	= $db->loadResult();
		return $authKey;		
	}	
	
	
	/**
	 * Function used to extend the auth key life span. Current set to 180 second.
	 * param : current token - string
	 *       : current authentication key - string
	 *       : user ip address - string
	 * return : boolean
	 */
	function updateAuthKey ($token='', $authKey='',$ip='')
	{
		$authKey	= "";	
	    $db    		=& $this->getDBO();
	    
		$config			=& CFactory::getConfig();
		$expiryPeriod	= $config->get( 'sessionexpiryperiod' );			
	    $expiryPeriod	= (empty($expiryPeriod)) ? "600" : $expiryPeriod;	    
				
		$query = "UPDATE ".$db->nameQuote('#__community_register_auth_token');
		$query .= " SET `created` = DATE_ADD(`created`, INTERVAL ". $expiryPeriod . " SECOND)";
		$query .= " WHERE `token` = " . $db->Quote($token);
		$query .= " AND `auth_key` = " . $db->Quote($authKey);
		$query .= " AND `ip` = " . $db->Quote($ip);		
		
		$db->setQuery($query);
		$db->query();								
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
						
		return true;		
	}
	
	function getUserByEmail($email)
	{
		$db    		=& $this->getDBO();
		
		$query	= 'SELECT * FROM `#__users`';
		$query	.= ' WHERE `email` = ' . $db->Quote($email);
		$db->setQuery($query);
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}		
		
		$result = $db->loadObject();		
		return $result;		
		
	}
	
	function getSuperAdministratorEmail()
	{
		$db    		=& $this->getDBO();
		
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
			
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}		
		
		$result = $db->loadObjectList();		
		return $result;		
		
	}
}
