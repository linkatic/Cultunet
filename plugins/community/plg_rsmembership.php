<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');

class plgCommunityPlg_RSMembership extends CApplications
{

	var $name 		= "RSMembership! Application";
	var $_name		= 'rsmembership';
	var $_path		= '';
	var $_user		= '';
	var $_my		= '';

    function plgCommunityPlg_RSMembership(& $subject, $config)
    {
    	$this->_path	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php';
		$this->_user	= & CFactory::getActiveProfile();
		$this->_my		= & CFactory::getUser();
			
		parent::__construct($subject, $config);
    }

	/**
	 * Return Itemid for RSMembership!
	 */	 	
	function getItemid()
	{
		$Itemid = (int) $this->params->get('itemid');
		if (empty($Itemid))
			$Itemid = JRequest::getInt('Itemid');
	    
	    return $Itemid;
	}
	
	function onProfileDisplay()
	{
		// Load language
		JPlugin::loadLanguage( 'plg_rsmembership', JPATH_ADMINISTRATOR );
				
		// Attach CSS
		$document	=& JFactory::getDocument();
		$css		= JURI::base() . 'plugins/community/plg_rsmembership/style.css';
		$document->addStyleSheet($css);
		
		if (!file_exists($this->_path))
		{
			$content  = '<div class="icon-nopost"><img src="'.JURI::base().'components/com_community/assets/error.gif" alt="" /></div>';	
			$content .= '<div class="content-nopost">'.JText::_('RSM_NOT_INSTALLED').'</div>';
		}
		else
		{
			require_once($this->_path);
			
			$user		 =& CFactory::getActiveProfile();
			$userName    = $user->getDisplayName();
			$userId 	 = $user->id;
			$memberships = $this->_getMemberships();
			$Itemid 	 = $this->getItemid();
			
			$cache =& JFactory::getCache('plgCommunityPlg_RSMembership');
			$cache->setCaching($this->params->get('cache', 1));
			$callback = array('plgCommunityPlg_RSMembership', '_getRSMembershipHTML');
			
			$content = $cache->call($callback, $userName, $userId, $memberships, $Itemid);
		}
		return $content;
	}
	
	function _getRSMembershipHTML($userName, $userId, $memberships, $Itemid)
	{
		ob_start();
		$content = '';
		
		if(!$memberships)
		{
			?>
			<div class="icon-nopost">
				<img src="<?php echo JURI::base(); ?>plugins/community/plg_rsmembership/favicon.png" alt="" />
			</div>
			<div class="content-nopost">
				<?php echo JText::sprintf('RSM_NO_MEMBERSHIPS', $userName); ?>
			</div>
			<?php
		}
		else
		{
		?>
			<div id="community-rsmembership-wrap">
			  <div class="ctitle"><?php echo JText::_('RSM_MEMBERSHIPS') ?></div>
				<table cellpadding="2" cellspacing="0" border="0" width="100%">
				<?php foreach ($memberships as $membership) { ?>
					<tr>
						<td width="15">
							<img src="<?php echo JURI::base(); ?>plugins/community/plg_rsmembership/favicon.png" alt="" />
						</td>
						<td valign="top">
							<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=membership&cid='.$membership->id.':'.JFilterOutput::stringURLSafe($membership->name).'&Itemid='.$Itemid); ?>"><?php echo $membership->name; ?></a>
						</td>
						
						<td align="right">
						<?php echo JText::sprintf('RSM_STARTED_EXPIRES', date(RSMembershipHelper::getConfig('date_format'), $membership->membership_start), date(RSMembershipHelper::getConfig('date_format'), $membership->membership_end)); ?>
						</td>
					</tr>
				<?php } ?>
				</table>
			</div>
		<?php
		}
		
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	/**
	 * Returns the list of memberships and its properties for the specific browsed user
	 * 
	 * @access private
	 * 
	 * returns	Array	An array of object list
	 **/	 	
	function _getMemberships()
	{
		$db		=& JFactory::getDBO();
		$limit	= '10';
		
		$db->setQuery("SELECT * FROM #__rsmembership_membership_users u LEFT JOIN #__rsmembership_memberships m ON (u.membership_id = m.id) WHERE u.`user_id`='".$this->_user->id."'");		
		return $db->loadObjectList();
	}
}
