<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelTerms extends JModel
{
	var $message;
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getTerms()
	{
		global $mainframe;
		
		$cid = JRequest::getInt('cid');
		
		$row =& JTable::getInstance('RSMembership_Terms','Table');
		$row->load($cid);
		
		if (!$row->published)
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
		
		return $row;
	}
}
?>