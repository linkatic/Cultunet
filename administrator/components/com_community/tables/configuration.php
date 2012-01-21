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
class CommunityTableConfiguration extends JTable
{
	var $name		= null;
	var $params		= null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__community_config' , 'name' , $db );
	}
}