<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	posted	boolean	Determines whether the current state is a posted event.
 * @param	search	string	The text that the user used to search 
 */
defined('_JEXEC') or die();
?>


<div class="cToolbarBand">
	<div class="bandContent">
		<form name="jsform-groups-search" method="post" action="">
			<input type="text" class="inputbox" name="search" value="" size="40" />
			<input type="submit" value="<?php echo JText::_('CC SEARCH BUTTON');?>" class="button" /> 
		      <?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
	
	<div class="bandFooter"><div class="bandFooter_inner"></div></div>
</div>

<div id="community-groups-wrap">
	<?php
	if( $posted )
	{
	?>
		<div class="dark-bg">
			<span style="float: left; width: 50%;"><?php echo JText::sprintf( 'CC SEARCH RESULT' , $search ); ?></span>
			<span style="float: right; text-align: right;">
				<?php echo JText::sprintf( (CStringHelper::isPlural($groupsCount)) ? 'CC SEARCH RESULT TOTAL MANY' : 'CC SEARCH RESULT TOTAL' , $groupsCount ); ?>
			</span>
			<div class="clr"></div>
		</div>
		
		<?php echo $groupsHTML; ?>
	<?php
	}
	?>
</div>