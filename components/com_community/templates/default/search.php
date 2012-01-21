<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	author		string
 * @param	$results	An array of user objects for the search result
 */
defined('_JEXEC') or die();
?>
<div class="people-search-form">
	<form name="jsform-search" method="get" action="">
		<input type="hidden" name="option" value="com_community" />
		<input type="hidden" name="view" value="search" />
		<input type="hidden" name="Itemid" value="<?php echo CRoute::_getDefaultItemid();?>">
		<div>
			<input type="text" class="inputbox" size="40" name="q" value="<?php echo $this->escape( $query ); ?>" />
			<input type="submit" value="<?php echo JText::_('CC BUTTON SEARCH');?>" class="button" name="Search" />
		</div>
		<div class="labelradio">
			<label class="lblradio"><input type="checkbox" name="avatar" id="avatar" style="margin-right: 5px;" value="1" class="radio"<?php echo ($avatarOnly) ? ' checked="checked"' : ''; ?>><?php echo JText::_("CC AVATAR ONLY"); ?></label>
		</div>
	</form>
</div>
<?php
if( $results )
{
?>
	<h2>
		<?php echo JText::_('CC SEARCH RESULTS');?>
	</h2>
	<?php echo $resultHTML;?>
<?php		
}
else if( empty( $results ) && !empty( $query ) )
{
?>
	<div class="people-not-found">
		<?php echo JText::_('CC NO RESULT FROM SEARCH');?>
	</div>
<?php
}
?>