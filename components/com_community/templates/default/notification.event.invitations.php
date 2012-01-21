<?php

/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */

defined('_JEXEC') or die();

?>

<div>
	<?php if (count ($rows) > 0 ) { ?>

		<div class="subject"><?php echo JText::_('CC NOTI NEW EVENT INVITATIONS') . ':'; ?></div>

	<?php }//end if ?>

	<?php foreach ( $rows as $row ) : ?>
	
	<div class="mini-profile" style="padding: 5px 5px 2px;" id="noti-pending-<?php echo $row->id; ?>">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		    <tr>
		        <td width="50">
		            <a href="<?php echo $row->url; ?>">
						<img width="32" src="<?php echo $row->eventAvatar; ?>" class="avatar" alt="<?php echo $this->escape($row->title); ?>"/>
					</a>
				</td>	
				<td valign="top">
					<div>
					    <span id="msg-pending-<?php echo $row->id; ?>">

					    	<?php echo JText::sprintf('CC NOTI INVITED YOU TO JOIN EVENT' , $row->invitor->getDisplayName() ,  $row->title); ?>

					    	<br />
						<span id="noti-answer-event-<?php echo $row->id; ?>">
						    <a class="icon-add-friend" style="text-indent: 0; padding-left: 20px;" href="javascript:void(0);" onclick="joms.jQuery('#noti-answer-event-<?php echo $row->id; ?>').remove(); jax.call('community' , 'notification,ajaxJoinInvitation' , '<?php echo $row->id; ?>', '<?php echo $row->eventid ?>');">

							    <?php echo JText::_('CC ACCEPT INVITATION'); ?>

						    </a>
						    <a class="icon-remove" style="text-indent: 0;" href="javascript:void(0);" onclick="joms.jQuery('#noti-answer-event-<?php echo $row->id; ?>').remove(); jax.call('community','notification,ajaxRejectInvitation','<?php echo $row->id; ?>', '<?php echo $row->eventid ?>');">

							    <?php echo JText::_('CC REJECT INVITATION'); ?>

						    </a>
						</span>
					    </span>

						<span id="error-pending-<?php echo $row->id; ?>">
					    </span>
						
					</div>
				</td>
	
			</tr>
		</table>
	</div>
	    
	<?php endforeach; ?>
</div>