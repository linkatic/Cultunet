<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<script type="text/javascript">
joms.jQuery(document).ready( function() {
	if ( joms.jQuery('.pagination').length > 0 ) {
		generatePagination();
	}
});

function generatePagination()
{
	var xurl = window.location.search.substring(1);
	q 	= xurl.split("&");
	
	var totalMessages 	= <?php echo $totalMessages; ?>;
	var prevClass 		= false;
	var nextClass 		= false;

	//var url = 'index.php?option=com_community&view=inbox';
	var url = window.location;
	
	// search within URL so determine whether we are using mod_rewrite or not
	var _url = String( url );
	var rewrite = _url.search(/option=com_community/i);
	
	// remove ?limitstart from url
	if ( rewrite <= 0 ) {
		url = _url.replace( /(\?limitstart)+\=[0-9]{2}/i, '' );
	}
	else {
		var t_url = [];
		//url = _url.replace( /(\&limitstart)+\=[0-9]{2}/i, '' );
		for ( var x = 0; x < q.length; x++ )
		{			
			if ( !q[x].test( /(limitstart)+\=[0-9]{2}/gi ) )
			{
				t_url[x] = q[x];
			}
		}
		var surl = _url.split("?");
		url = surl[0] + '?' + t_url.join('&');
	}
	
	var limitNext, limitPrev;
	var currentPage = 1, totalPage = 1;

	// get total page
	totalPage = Math.ceil( totalMessages / 20 );
	
	newP = '';
	
	if ( totalMessages > 20 ) {
	
		for ( i = 0; i < q.length; i++ ) {

			ft = q[i].split("=");
		
		
			if ( ft[0] == 'limitstart' ) {
			
				var limit = parseInt( ft[1] );
				var tempTotal = limit + 20;
				
				if ( tempTotal <= totalMessages ) {
					// no mod_rewrite
					if ( rewrite > 0 ) {
						limitNext = '&limitstart=' + tempTotal;
					}
					else {
						limitNext = '?limitstart=' + tempTotal;
					}
					nextClass = true;
				}
				else {
					nextClass = false;
				}
				
				if ( limit <= 20  ) {
					limitPrev = '';
					prevClass = true;
				}
				else if ( limit > 20 ) {
					// no mod_rewrite
					if ( rewrite > 0 ) {
						limitPrev = '&limitstart=' + ( limit - 20 );
					}
					else {
						limitPrev = '?limitstart=' + ( limit - 20 );
					}
					prevClass = true;
				}
				
				// set current page marker
				currentPage = Math.ceil( ft[1] / 20 ) + 1;
			}
			else {
				if ( rewrite > 0 ) {
					limitNext = '&limitstart=20';
				}
				else {
					limitNext = '?limitstart=20';
				}
				nextClass = true;
				currentPage = 1;
			}		
		}
		newP += '<div id="pagination" class="black-button">';
		
		if ( nextClass ) {
			newP += '	<a href="' + url + limitNext + '" class="btn-blue btn-next"><span><?php echo JText::_("CC NEXT"); ?></span></a>';
		}
		else {
			newP += '	<span class="btn-blue btn-next-disabled"><span><?php echo JText::_("CC NEXT"); ?></span></span>';
		}
			
		// prev button
		if ( prevClass ) {
			newP += '	<a href="' + url + '" class="btn-blue btn-prev"><span><?php echo JText::_("CC PREV"); ?></span></a>';
		}
		else {
			newP += '	<span class="btn-blue btn-prev-disabled"><span><?php echo JText::_("CC PREV"); ?></span></span>';
		}
		
		var pageString	= '<?php echo JText::sprintf("CC SHOW PAGE OF TOTAL PAGE", "' + currentPage + '", "' + totalPage + '"); ?>';
		
		newP += '<div class="pagenum">' + pageString + '</div>';
		newP += '</div>';
		newP += '<div class="clr"></div>';	
		
				
	}

	joms.jQuery('.pagination').html(newP);
}
</script>
<div class="inbox iphone">
	<div class="inbox-toolbar">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
		    <tr>
		        <td width="20" align="center">
		            <input type="checkbox" name="select" class="checkbox" onclick="checkAll();" id="checkall" />
				</td>
		        <td>
		        	<select name="inbox-action" id="inbox-action" onchange="submitAction();">
		        		<option value="0" class="none"><?php echo JText::_('CC INBOX SELECT ACTION'); ?></option>
		        		<?php if ( !JRequest::getVar('task') == 'sent' ) : ?>
		        		<option value="markRead"><?php echo JText::_('CC INBOX MARK READ'); ?></option>
		        		<option value="markUnread"><?php echo JText::_('CC INBOX MARK UNREAD'); ?></option>
		        		<?php endif; ?>
		        		<option value="deleteMsg"><?php echo JText::_('CC INBOX REMOVE MESSAGE'); ?></option>
		        	</select>
				</td>
		    </tr>
		</table>
	</div>
	
	
	<div class="inbox-list" id="inbox-listing">
		<?php foreach ( $messages as $message ) : ?>
		<div class="<?php echo $message->isUnread ? 'inbox-unread' : 'inbox-read'; ?>" id="message-<?php echo $message->id; ?>">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
		    <tr>
		        <td width="20" align="center">
		            <input type="checkbox" name="message[]" value="<?php echo $message->id; ?>" class="checkbox" onclick="checkSelected();" />
				</td>
	
		        <td width="50">
		        	<?php if((JRequest::getVar('task') == 'sent') && (! empty($message->smallAvatar[0])) ) { ?>
		            	<img width="32" src="<?php echo $message->smallAvatar[0]; ?>" alt="<?php echo JString::ucfirst( $message->to_name[0] ); ?>" class="avatar" />
		            <?php } else { ?>
		            	<img width="32" src="<?php echo $message->avatar; ?>" alt="<?php echo JString::ucfirst( $message->from_name ); ?>" class="avatar" />
		            <?php }//end if ?>
				</td>
				<td>
					<h3 class="name">
						<a class="subject" href="<?php echo CRoute::_('index.php?option=com_community&view=inbox&task=read&msgid='. $message->parent); ?>">
						
							<?php if($message->isUnread) { ?>
								<img style="vertical-align:middle;" id="new-message-<?php echo $message->id;?>" src="<?php echo JURI::base(); ?>components/com_community/templates/default/images/new.gif" alt="" />
							<?php } else { ?>
								<img style="vertical-align:middle; display:none;" id="new-message-<?php echo $message->id;?>" src="<?php echo JURI::base(); ?>components/com_community/templates/default/images/new.gif" alt="" />					
							<?php } ?>
							<?php echo $message->subject; ?>
						</a>
					</h3>
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
				<td width="30" align="center">
	                
				</td>
		    </tr>
		</table>
			<a style="width: 100%; z-index: 1000; cursor: pointer; position: absolute; top: 0; left: 0; height: 100%; display: block; text-decoration: none;" href="<?php echo CRoute::_('index.php?option=com_community&view=inbox&task=read&msgid='. $message->parent); ?>">&nbsp;</a>
			<a style="width: 30px; z-index: 1000; cursor: pointer; position: absolute; top: 10px; right: 5px; height: 30px; display: block; text-decoration: none;" href="javascript:jax.call('community', 'inbox,ajaxRemoveFullMessages', <?php echo $message->id; ?>);" class="remove" style="" title="<?php echo JText::_('CC INBOX REMOVE CONVERSATION'); ?>"><?php echo JText::_('CC INBOX REMOVE'); ?></a>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
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
function deleteMarked()
{
	if ( confirm('<?php echo JText::_('CC INBOX REMOVE CONFIRM'); ?>') ) {
	    joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
	        if ( joms.jQuery(this).attr('checked') ) {
				jax.call( 'community', 'inbox,ajaxRemoveFullMessages', joms.jQuery(this).attr('value') );
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
function submitAction()
{
	switch ( joms.jQuery('#inbox-action').val() )
	{
		case 'markRead':
			setAllAsRead();
			break;
		
		case 'markUnread':
			setAllAsUnread();
			break;
		
		case 'deleteMsg':
			deleteMarked();
			break;
		
		default:
			break;
	}
	joms.jQuery('#inbox-action option.none').attr('selected', 'selected');
	return false;
}
</script>