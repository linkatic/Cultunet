<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CTableCategory extends JTable
{

	var $id 			= null;
	var $name 			= null;
	var $description 	= null;

  
	/**
	 * Constructor
	 */	 	
	function __construct( &$db )
	{
		parent::__construct( '#__community_groups_category', 'id', $db );
	}
}