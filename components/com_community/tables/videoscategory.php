<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CTableVideosCategory extends JTable 
{
	var $id 			= null;
	var $name 			= null;
  	var $description	= null;
  	var $published		= null;

	/**
	 * Constructor
	 */
	function CTableVideosCategory( &$db )
	{
		parent::__construct( '#__community_videos_category', 'id', $db );
	}	
}