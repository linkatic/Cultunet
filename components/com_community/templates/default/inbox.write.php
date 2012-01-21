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
function disableFormField(){

	//change the background color to light grey
	document.getElementById('to').style.backgroundColor      = "#cfcfcf";
	document.getElementById('subject').style.backgroundColor = "#cfcfcf";
	document.getElementById('message-body').style.backgroundColor    = "#cfcfcf";

    //text field
	document.getElementById('to').readonly = true;
	document.getElementById('subject').readonly = true;
	document.getElementById('message-body').readonly = true;

    //button
    document.getElementById('submitBtn').disabled = true;
    //document.getElementById('cancelBtn').disabled = true;

}//end disableFormField

var yPos;

function addFriendName()
{
    var inputs 		= [];    
    
    joms.jQuery('#selections option:selected').each( function() {
		inputs.push(this.value);				
	});

    var x = inputs.join(', ');
    joms.jQuery('#to').val(x);
}
</script>
<div class="app-box">
<form name="jsform-inbox-write" class="community-form-validate composeForm" id="writeMessageForm" action="<?php echo CRoute::getURI(); ?>" method="post" onsubmit="disableFormField();">
<?php
if( $totalSent >=  $maxSent && $maxSent != 0 )
{
?>
	<div class="error-box"><?php echo JText::_('CC PM LIMIT REACHED');?></div>
<?php
}
else
{
?>
<table cellpadding="0" cellspacing="0" border="0" width="98%">
	<tr>
	    <td>
			<table class="formtable" cellspacing="1" cellpadding="0">
			<tr>
				<td colspan="2">
					<?php echo $beforeFormDisplay;?>
				</td>
			</tr>
			<!-- name -->
			<tr>
				<td class="key">
					<label for="name" class="label title">
						*<?php echo ($useRealName == '1') ? JText::_('CC COMPOSE TO REALNAME') : JText::_('CC COMPOSE TO USERNAME'); ?>
					</label>
				</td>
				<td class="value">
					<input id="to" name="to" class="inputbox fullwidth required text ac_input" type="text" autocomplete="off" value="<?php echo $data->to; ?>" />
				</td>
			</tr>
			
			
			<!-- subject -->
			<tr>
				<td class="key">
					<label for="description" class="label title">
						*<?php echo JText::_('CC COMPOSE SUBJECT'); ?>
					</label>
				</td>
				<td class="value">
					<input id="subject" class="inputbox fullwidth required text" name="subject" type="text" value="<?php echo $data->subject; ?>" />
				</td>
			</tr>
			
			
			<!-- message -->
			<tr>
				<td class="key">
					<label for="description" class="label title">
						*<?php echo JText::_('CC COMPOSE MESSAGE'); ?>
					</label>
				</td>
				<td class="value">
					<textarea id="message-body" name="body" class="inputbox fullwidth required textarea"><?php echo $data->body; ?></textarea>
				</td>
			</tr>
			
			<?php
			if( $maxSent != 0 )
			{
			?>
			<!-- group hint -->
			<tr class="noLabel">
				<td class="key"></td>
				<td class="value">
					<div class="hints"><?php echo JText::sprintf('CC PM LIMIT' , $maxSent); ?></div>
					<div class="hints"><?php echo JText::sprintf('CC PM LIMIT REMAINING' , $totalSent , $maxSent); ?></div>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td class="key"></td>
				<td class="value"><span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php echo $afterFormDisplay;?>
				</td>
			</tr>
			<!-- buttons -->
			<tr class="noLabel">
				<td class="key"></td>
				<td class="value">
					<input type="hidden" name="action" value="doSubmit"/>					
					<input id="submitBtn" class="button validateSubmit" name="submitBtn" type="submit" value="<?php echo JText::_('CC BUTTON SUBMIT'); ?>" />
				</td>
			</tr>
			
			
			</table>
			
		</td>
	    <td width="150" valign="top">
			<div class="receiverList" style="text-align: center;">
		    	<select size="15" id="selections" name="selections[]" class="inputbox text">
		    	    <optgroup label="<?php echo JText::_('CC FRIENDS LIST');?>">
						<?php foreach ( $rows as $row ) : ?>
						<option value="<?php echo $row->getDisplayName(); ?>" id="<?php echo $row->id; ?>"><?php echo $row->getDisplayName(); ?></option>
						<?php endforeach; ?>
					</optgroup>
			    </select>
			    <input type="button" onclick="addFriendName();" class="button" value="<?php echo JText::_('CC BUTTON ADD AS RECIPIENT'); ?>" style="width: 140px;" />
			</div>
		</td>
	</tr>
</table>
</form>
<script type="text/javascript">
    cvalidate.init();
    cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
</script>
<?php
}
?>
</div>