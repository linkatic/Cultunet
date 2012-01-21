<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();

$toolbarClass 	= array( 'frontpage' => '', 'profile' => '', 'friends'=>'', 'inbox'=>'' );
$toolbar_active = JRequest::getVar( 'view','frontpage','REQUEST' );

//override the active view. If view=search, use frontpage
if($toolbar_active == 'search')
{
	$toolbar_active	= 'frontpage';
}
else if($toolbar_active == 'profile')
{
	if(!$isMine){
		$toolbar_active	= 'frontpage';
	}
}
$toolbarClass[$toolbar_active] = 'active';
//$toolbarClass['profile'] = (!$isMine && $toolbar_active == 'profile') ? '' : $toolbarClass['profile']; 

$uri	= CRoute::_('index.php?option=com_community' , false );
$uri	= base64_encode($uri);
?>
<script type="text/javascript">
window.scrollTo(0, 1);

joms.jQuery(document).ready( function() {
	if ( joms.jQuery('#back-toolbar').length > 0 ) {
		html = joms.jQuery('#back-toolbar').html();
		joms.jQuery('#back-toolbar-container').html( html ).addClass( 'black-button' );
		joms.jQuery('#back-toolbar').hide();
	}
});

</script>
<div id="back-toolbar-container" class=""></div>
<div id="sitelogo">
	<a class="logo" href="<?php echo CRoute::_('index.php?option=com_community'); ?>" title="<?php echo $config->getValue('sitename'); ?>">
		<span><?php echo $config->getValue('sitename'); ?></span>
	</a>
	<a class="logout" href="javascript:void(0);" onclick="document.iphonelogout.submit();"><?php echo JText::_('CC IPHONE LOGOUT'); ?></a>
</div>
<div id="simple-toolbar">
	<ul class="toolbar">
		<li id="toolbar-frontpage" class="<?php echo $toolbarClass['frontpage']; ?>"><a href="<?php echo CRoute::_('index.php?option=com_community&view=frontpage'); ?>">Home</a></li>
		<li id="toolbar-profile" class="<?php echo $toolbarClass['profile']; ?>"><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $my->id); ?>">Profile</a></li>
		<li id="toolbar-friends" class="<?php echo $toolbarClass['friends']; ?>"><a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid='. $my->id); ?>">Friends</a></li>
		<li id="toolbar-inbox" class="<?php echo $toolbarClass['inbox']; ?>"><a href="<?php echo CRoute::_('index.php?option=com_community&view=inbox'); ?>">Inbox</a></li>
	</ul>
	<div style="clear: left;"></div>
</div>
<form action="index.php" method="post" name="iphonelogout" id="iphonelogout">
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $uri; ?>" />
</form>