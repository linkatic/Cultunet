<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	$discussions	An array of discussions object
 * @param	$groupId		The group id
 * @param	$total			The number of total discussions 
 */
defined('_JEXEC') or die();

if( $discussions )
{
	for($i = 0; $i < count( $discussions ); $i++ )
	{
		$row	=& $discussions[$i];
?>



	<div id="discuss_<?php echo $row->id; ?>" class="group-discussion">
        <div class="group-discussion-title">
            <a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&groupid=' . $groupId. '&topicid=' . $row->id ); ?>">
                <?php echo $row->title; ?>
            </a>
            <div class="group-discussion-replies">
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&groupid=' . $groupId . '&topicid=' . $row->id ); ?>">
                    <?php echo JText::sprintf( (CStringHelper::isPlural($row->count)) ? 'CC TOTAL REPLIES MANY' : 'CC TOTAL REPLIES', $row->count); ?>
                </a>
            </div>
        </div>
        
        <div class="clr"></div>
        <div class="group-discussion-author small">
            <?php if( isset( $row->lastreplier ) && !empty( $row->lastreplier ) ) { ?>
            <span class="groups-news-author">
                <?php echo JText::sprintf('CC DISCUSSION LAST REPLIED', '<a href="' . CUrlHelper::userLink( $row->lastreplier->post_by->id ) . '">' . $row->lastreplier->post_by->getDisplayName() . '</a>', JHTML::_('date', $row->lastreplier->date, JText::_('DATE_FORMAT_LC')) ); ?>
            </span>
            <?php } else { ?>
            <span class="groups-news-author">
				<?php echo JText::sprintf('CC DISCUSS STARTED BY' , '<a href="' . CUrlHelper::userLink( $row->user->id ) . '">' . $row->user->getDisplayName() . '</a>'); ?>
			</span>
            <?php } ?>
        </div>
	</div>
	<?php
	}
	?>
<?php
}
else
{
?>
	<div class="empty"><?php echo JText::_('CC NO DISCUSSIONS'); ?></div>
<?php
}
?>