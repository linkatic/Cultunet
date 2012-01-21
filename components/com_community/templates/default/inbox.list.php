<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>


<div class="inbox-toolbar">
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
	    <tr>
	        <td width="30" align="center">
	            <input type="checkbox" name="select" class="checkbox" onclick="checkAll();" id="checkall" />
			</td>
	        <td>
	            <?php if ( !JRequest::getVar('task') == 'sent' ) { ?>
					<a href="javascript:void(0);" onclick="setAllAsRead();"><?php echo JText::_('CC INBOX MARK READ'); ?></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:void(0);" onclick="setAllAsUnread();"><?php echo JText::_('CC INBOX MARK UNREAD'); ?></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:void(0);" onclick="deleteMarked('inbox');"><?php echo JText::_('CC INBOX REMOVE MESSAGE'); ?></a>&nbsp;
				<?php } else { ?>
					<a href="javascript:void(0);" onclick="deleteMarked('sent');"><?php echo JText::_('CC INBOX REMOVE MESSAGE'); ?></a>&nbsp;
				<?php } ?>
			</td>
	    </tr>
	</table>
</div>


<div class="inbox-list" id="inbox-listing">
	<?php foreach ( $messages as $message ) : ?>
	<div class="<?php echo $message->isUnread ? 'inbox-unread' : 'inbox-read'; ?>" id="message-<?php echo $message->id; ?>">
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
	    <tr>
	        <td width="30" align="center">
	            <input type="checkbox" name="message[]" value="<?php echo $message->id; ?>" class="checkbox" onclick="checkSelected();" />
			</td>

	        <td width="50">
	        	<?php if((JRequest::getVar('task') == 'sent') && (! empty($message->smallAvatar[0])) ) { ?>
	            	<img width="32" src="<?php echo $message->smallAvatar[0]; ?>" alt="<?php echo $this->escape( JString::ucfirst( $message->to_name[0] ) ); ?>" class="avatar" />
	            <?php } else { ?>
	            	<img width="32" src="<?php echo $message->avatar; ?>" alt="<?php echo $this->escape( JString::ucfirst( $message->from_name ) ); ?>" class="avatar" />
	            <?php }//end if ?>
			</td>
			<td>
				<a class="subject" href="<?php echo CRoute::_('index.php?option=com_community&view=inbox&task=read&msgid='. $message->parent); ?>">
					<?php echo $message->subject; ?>
				</a>
				<div class="small">
					<?php if((JRequest::getVar('task') == 'sent') && (! empty($message->smallAvatar[0])) ) {
				    	echo $message->to_name[0] . ',';
					} else {
						echo $message->from_name . ',';
					}//end if  ?> 
					<?php
						$postdate =  CTimeHelper::getDate($message->posted_on);
						echo $postdate->toFormat('%d %b %Y, %I:%M %p'); 
					?>
				</div>
			</td>
			<td width="20" align="center">
                <a href="javascript:jax.call('community', 'inbox,ajaxRemoveFullMessages', <?php echo $message->id; ?>);" class="remove" style="" title="<?php echo JText::_('CC INBOX REMOVE CONVERSATION'); ?>"><?php echo JText::_('CC INBOX REMOVE'); ?></a>
			</td>
	    </tr>
	</table>
	</div>
	<?php endforeach; ?>
</div>
<div class="pagination-container">
	<?php echo $pagination; ?>
</div>
<script type="text/javascript">
function checkAll()
{
	joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
	    if ( joms.jQuery('#checkall').attr('checked') )
			joms.jQuery(this).attr('checked', true);
  		else
  		    joms.jQuery(this).attr('checked', false);
	});
	return false;
}
function checkSelected()
{
	var sel;
	sel = false;
    joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
        if ( !joms.jQuery(this).attr('checked') )
            joms.jQuery('#checkall').attr('checked', false);
    });
}
function deleteMarked(task)
{
	if ( confirm('<?php echo JText::_('CC INBOX REMOVE CONFIRM'); ?>') ) {
	    joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
	        if ( joms.jQuery(this).attr('checked') ) {
	        	if(task == 'inbox')
					jax.call( 'community', 'inbox,ajaxRemoveFullMessages', joms.jQuery(this).attr('value'));
				else
					jax.call( 'community', 'inbox,ajaxRemoveSentMessages', joms.jQuery(this).attr('value'));
			}
		});
		return false;
	}
}
function markAsRead( id )
{
    joms.jQuery('#message-'+id).removeClass('inbox-unread');
    joms.jQuery('#message-'+id).addClass('inbox-read');
    joms.jQuery('#new-message-'+id).hide();
    joms.jQuery("#message-"+id+" INPUT[type='checkbox']").attr('checked', false);
    joms.jQuery('#checkall').attr('checked', false);
}
function markAsUnread( id )
{
    joms.jQuery('#message-'+id).removeClass('inbox-read');
    joms.jQuery('#message-'+id).addClass('inbox-unread');
    joms.jQuery('#new-message-'+id).show();
    joms.jQuery("#message-"+id+" INPUT[type='checkbox']").attr('checked', false);
    joms.jQuery('#checkall').attr('checked', false);
}
function setAllAsRead()
{
    joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
        if ( joms.jQuery(this).attr('checked') ) {
            if ( joms.jQuery('#message-'+joms.jQuery(this).attr('value')).hasClass('inbox-unread') ) {
            	jax.call( 'community', 'inbox,ajaxMarkMessageAsRead', joms.jQuery(this).attr('value') );
            }
		}
    });
}
function setAllAsUnread()
{
    joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
        if ( joms.jQuery(this).attr('checked') )
            if ( joms.jQuery('#message-'+joms.jQuery(this).attr('value')).hasClass('inbox-read') ) {
            	jax.call( 'community', 'inbox,ajaxMarkMessageAsUnread', joms.jQuery(this).attr('value') );
            }
    });
}
</script>