<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelRSMembership extends JModel
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function getCode()
	{
		$code = RSMembershipHelper::getConfig('global_register_code');
		return $code;
	}
}
?>