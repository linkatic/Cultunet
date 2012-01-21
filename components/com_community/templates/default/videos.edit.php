<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * 
 */
defined('_JEXEC') or die();
?>
<form name="editvideo" action="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=saveVideo'); ?>" method="post">
<table cellspacing="0" class="admintable" border="0" width="100%">
	<tbody>
		
		<tr>
			<td class="key"><label class="label" for="title"><?php echo JText::_('CC VIDEO TITLE');?></label></td>
			<td>:</td>
			<td>
				<span>
					<input type="text" id="title" name="title" class="inputbox" value="<?php echo $this->escape($video->title);?>" style="width: 300px;" />
				</span>
			</td>
		</tr>
		<tr>
			<td class="key"><label class="label" for="description"><?php echo JText::_('CC VIDEO DESCRIPTION');?></label></td>
			<td>:</td>
			<td>
				<textarea name="description" style="width: 300px;" rows="8" id="description"><?php echo $this->escape($video->description); ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="key"><label class="label" for="category"><?php echo JText::_('CC VIDEO CATEGORY');?></label></td>
			<td>:</td>
			<td>
				<?php  echo $categoryHTML; ?>
			</td>
		</tr>
		
		<?php
			if($showPrivacy)
			{
		?>
		<tr>
			<td class="key"><label class="label" for="description"><?php echo JText::_('CC VIDEO WHO CAN SEE');?></label></td>
			<td>:</td>
			<td>
				
				<table>
				<tr>
				<td class="listvalue" width="">
				<input id="privacypublic" type="radio" <?php if($video->permissions == 0) { ?>checked="checked" <?php } ?> value="0" name="permissions"/>
				<label class="lblradio" for="privacypublic"><?php echo JText::_('CC PRIVACY PUBLIC');?></label><br/>
				
				<input id="privacyregistered" type="radio" name="permissions" <?php if($video->permissions == 20) { ?>checked="checked" <?php } ?> value="20"/>
				<label class="lblradio" for="privacyregistered"><?php echo JText::_('CC PRIVACY SITE MEMBERS');?></label><br/>
				
				<input id="privacyfriends" type="radio" name="permissions" <?php if($video->permissions == 30) { ?>checked="checked" <?php } ?> value="30"/>
				<label class="lblradio" for="privacyfriends"><?php echo JText::_('CC PRIVACY FRIENDS');?></label><br/>
				
				<input id="privacyme" type="radio" name="permissions" <?php if($video->permissions == 40) { ?>checked="checked" <?php } ?> value="40"/>
				<label class="lblradio" for="privacyme"><?php echo JText::_('CC PRIVACY ME');?></label>
				</td>
				</tr>
				</table>
				
			</td>
		</tr>
		<?php
			}
		?>	
		
	</tbody>
</table>
<input type="hidden" name="id" value="<?php echo $video->id;?>" />
<input type="hidden" name="option" value="com_community" />
<input type="hidden" name="view" value="videos" />
<input type="hidden" name="task" value="saveVideo" /> 
<input type="hidden" name="redirectUrl" value="<?php echo $redirectUrl;?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>