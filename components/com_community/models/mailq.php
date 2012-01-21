<?php
/**
 * @category	Model
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

class CommunityModelMailq extends JCCModel
{
	/**
	 * take an object with the send data
	 * $recipient, $body, $subject, 	 
	 */	 	
	function add($recipient, $subject, $body , $templateFile = '' , $params = '' , $status = 0)
	{
		$db	 = &$this->getDBO();
		
		$date =& JFactory::getDate();
		$obj  = new stdClass();
		
		$obj->recipient = $recipient;
		$obj->body 		= $body;
		$obj->subject 	= $subject;
		$obj->template	= $templateFile;
		$obj->params	= ( is_object( $params ) && method_exists( $params , 'toString' ) ) ? $params->toString() : '';	
		$obj->created	= $date->toMySQL();
		$obj->status	= $status;
		
		$db->insertObject( '#__community_mailq', $obj );
	}
	
	/**
	 * Restrive some emails from the q and delete it
	 */	 	
	function get($limit = 100 )
	{
		$db	 = &$this->getDBO();
		
		$sql = "SELECT * FROM `#__community_mailq` WHERE `status`='0' LIMIT 0," . $limit;

		$db->setQuery( $sql );
		$result = $db->loadObjectList();
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
		}
		
		return $result;
	}
	
	/**
	 * Change the status of a message
	 */	 	
	function markSent($id)
	{
		$db	 = &$this->getDBO();
		
		$sql = "SELECT * FROM #__community_mailq WHERE `id`=" . $db->Quote($id);
		$db->setQuery( $sql );
		$obj = $db->loadObject();
		
		$obj->status = 1;
		$db->updateObject( '#__community_mailq', $obj, 'id' );
	}
	
	function purge(){
	}
	
	function remove(){
	}
}
