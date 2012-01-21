<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Jom Social Table Model
 */
class CommunityTableUsers extends JTable
{
	var $userid			= null;
	var $status			= null;
	var $points			= null;
	var $posted_on		= null;
	var $avatar			= null;
	var $thumb			= null;
	var $invite			= null;
	var $params			= null;
	var $view			= null;
	var $friendcount	= null;
	
	function __construct(&$db)
	{
		parent::__construct('#__community_users','userid', $db);
	}

	function getFriendCount()
	{
		$db		=& JFactory::getDBO();
				
		$query = 'SELECT COUNT(*) FROM '.$db->nameQuote( '#__community_connection').' AS a';		
		$query .= ' INNER JOIN '.$db->nameQuote( '#__users').' AS b ON a.connect_to = b.id';
		$query .= ' WHERE a.connect_from = '.$db->Quote( $this->userid );
		$query .= ' AND a.STATUS = 1';

		$db->setQuery( $query );
		$count	= $db->loadResult();
		
		return $count;
	}
}