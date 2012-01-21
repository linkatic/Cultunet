<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CTableConnect extends JTable
{
	var $connectid	= null;
	var $type		= null;
	var $userid		= null;
	
	/**
	 * Constructor
	 */	 	
	function __construct( &$db )
	{
		parent::__construct( '#__community_connect_users', 'connectid', $db );
	}
	
	function store()
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__community_connect_users '
				. 'WHERE ' . $db->nameQuote( 'connectid' ) . '=' . $db->Quote( $this->connectid );
		
		$db->setQuery($query);
		$result	= $db->loadResult(); 
		
		if( !$result )
		{
			$obj			= new stdClass();
			$obj->connectid	= $this->connectid;
			$obj->type		= $this->type;
			$obj->userid	= $this->userid;
			return $db->insertObject( '#__community_connect_users' , $obj );
		}
		
		// Existing table, just need to update
		return $db->updateObject( '#__community_connect_users', $this, 'connectid' , false );
	}
}