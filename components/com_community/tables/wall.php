<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Wall object
 */ 
class CTableWall extends JTable
{
	/** Primary key **/
	var $id 			= null;
	
	/** The unique id of the specific app type **/
	var $contentid		= null;
	
	/** The user id that posted **/
	var $post_by		= null;
	
	/** The IP address of the poster **/
	var $ip				= null;
	
	/** Message **/
	var $comment		= null;
	
	/** Date the comment is posted **/
	var $date			= null;
	
	/** Publish status of the wall **/
	var $published		= null;
	
	/** Application type **/
	var $type			= null;

	/**
	 * Constructor
	 */	 	
	function __construct( &$db )
	{
		parent::__construct( '#__community_wall', 'id', $db );
	}
}
