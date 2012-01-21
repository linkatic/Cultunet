<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.plugin.plugin');

if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php'))
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');
else
	return;

class plgSystemRSMembership extends JPlugin {
	
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @since 1.5
	 */
	
	function plgSystemRSMembership(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onAfterInitialise()
	{
		RSMembershipHelper::executeSystemPlugin('init');
	}
	
	function onAfterDispatch()
	{
		RSMembershipHelper::executeSystemPlugin('dispatch');
	}
	
	function onAfterRoute()
	{
		RSMembershipHelper::executeSystemPlugin('route');
	}
	
	function onAfterRender()
	{
		RSMembershipHelper::executeSystemPlugin('render');
	}
	
	function onCreateModuleQuery($extra)
	{
		$extra->where .= RSMembershipHelper::getModulesWhere();
	}
}