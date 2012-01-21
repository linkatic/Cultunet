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
<div id="community-events-wrap">

	<!--SEARCH FORM-->
	<div class="event-search-form">
		<form name="jsform-events-search" method="post" action="">
			<?php echo $beforeFormDisplay;?>
			<input type="text" class="inputbox" name="search" value="" size="50" />
			<?php echo $afterFormDisplay;?>
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="submit" value="<?php echo JText::_('CC SEARCH BUTTON');?>" class="button" />
		</form>
	</div>
	<!--SEARCH FORM-->
		
	<?php
	if( $posted )
	{
	?>
	<!--SEARCH DETAIL-->
	<div class="event-search-detail">
		
		<span class="search-detail-left">
			<?php echo JText::sprintf( 'CC SEARCH RESULT' , $search ); ?>
		</span>
		
		<span class="search-detail-right">
			<?php echo JText::sprintf( (CStringHelper::isPlural($eventsCount)) ? 'CC SEARCH RESULT TOTAL MANY' : 'CC SEARCH RESULT TOTAL' , $eventsCount ); ?></span>
		<div style="clear:both;"></div>
		
	</div>
	<!--SEARCH DETAIL-->
	
	<?php echo $eventsHTML; ?>
	<?php
	}
	?>
</div>