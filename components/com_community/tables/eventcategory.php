<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

class CTableEventCategory extends JTable
{

	var $id 			= null;
	var $name 			= null;
	var $description 	= null;


	/**
	 * Constructor
	 */
	function __construct( &$db )
	{
		parent::__construct( '#__community_events_category', 'id', $db );
	}
}