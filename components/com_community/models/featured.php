<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Profile
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

jimport( 'joomla.filesystem.file');

// Deprecated since 1.8.x to support older modules / plugins
CFactory::load( 'tables' , 'featured' );

class CommunityModelFeatured extends JCCModel
{
	function isExists( $type, $cid )
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM #__community_featured '
				. 'WHERE type=' . $db->Quote( $type ) . ' '
				. 'AND cid=' . $db->Quote( $cid );
		$db->setQuery($query);
		$exists	= ( $db->loadResult() >= 1) ? true : false;
		return $exists;
	}
}