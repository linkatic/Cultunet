<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	$bulletins	An array of discussions object
 * @param	$groupId		The group id
 */
defined('_JEXEC') or die();

if( $bulletins )
{
	for($i = 0; $i < count( $bulletins ); $i++ )
	{
		$row	=& $bulletins[$i];
?>
	<div id="bulletin_<?php echo $row->id; ?>" class="group-bulletin-item <?php echo $i == 0 ? 'group-bulletin-item-intro' : ''; ?>">
		<div class="groups-news-title">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewbulletin&groupid=' . $groupId . '&bulletinid=' . $row->id);?>">
				<?php echo $row->title; ?>
			</a>
		</div>
		<div class="groups-news-author">
			<small><?php echo JHTML::_('date' , $row->date, JText::_('DATE_FORMAT_LC')); ?></small>
			<small>
				<?php echo JText::sprintf( 'by <a href="%2$s">%1$s</a>' , $row->creator->getDisplayName() , CRoute::_('index.php?option=com_community&view=profile&userid=' . $row->creator->id ) ); ?>
			</small>
		</div>
		
		<?php // Only show content for 1st item only ?>
		<?php if ( $i == 0 ) { ?>
		<div class="groups-news-message">
			<?php echo $row->message;?>
		</div>
		<?php } ?>
		
	</div>
	<?php
	}
}
else
{
?>
	<div class="groups-news-empty"><?php echo JText::_('CC NO BULLETINS'); ?></div>
<?php
}	
?>