<?php
/**
 * @category	Elements
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );

class JElementTwitter extends JElement
{
	var	$_name = 'Twitter';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$lang =& JFactory::getLanguage();
		$lang->load( 'com_community', JPATH_ROOT);
		
		if(!JPluginHelper::importPlugin('community' , 'twitter' ) )
		{
		    return JText::sprintf('CC UNABLE TO LOAD PLUGIN ELEMENT', 'Twitter' );
		}

	    $my         = CFactory::getUser();
	    $consumer   = plgCommunityTwitter::getConsumer();
	    $oauth    	=& JTable::getInstance( 'Oauth' , 'CTable' );

	    ob_start();

		if( !$oauth->load( $my->id , 'twitter') || empty($oauth->accesstoken) )
		{
		    $oauth->userid        = $my->id;
		    $oauth->app             = 'twitter';
			$oauth->requesttoken	= serialize( $consumer->getRequestToken() );

			$oauth->store();
		?>
		<div><?php echo JText::_('CC YOU WILL NEED TO SIGN IN WITH TWITTER ACCOUNT');?></div>
		<a href="<?php echo $consumer->getRedirectUrl();?>"><img src="<?php echo JURI::root();?>components/com_community/assets/twitter.png" border="0" alt="here" /></a>
		<?php
		}
		else
		{
		    //User is already authenticated and we have the proper tokens to fetch data.
		    $url    = CRoute::_( 'index.php?option=com_community&view=oauth&task=remove&app=twitter' );
		?>
		    <div><?php echo JText::sprintf('CC REMOVE TWITTER ACCESS' , $url );?></div>
		<?php
		}
		$html   = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
}
