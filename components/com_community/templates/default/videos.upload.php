<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>


<form name="uploadVideo" id="uploadVideo" method="post" action="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=upload');?>" enctype="multipart/form-data">


<table class="cWindowForm" cellspacing="1" cellpadding="0">

<!-- video file -->
<tr>
	<td class="cWindowFormKey">
		<label for="file" class="label title">
			*<?php echo JText::_('CC SELECT VIDEO FILE');?>
		</label>
	</td>
	<td class="cWindowFormVal">
		<input type="file" name="videoFile" id="file" class="inputbox required" />
		<div class="hints"><?php echo JText::sprintf('CC MAXIMUM UPLOAD LIMIT', $uploadLimit); ?></div>
	</td>
</tr>


<!-- video title -->
<tr>
	<td class="cWindowFormKey">
		<label for="videoTitle" class="label title">
			*<?php echo JText::_('CC VIDEO TITLE'); ?>
		</label>
	</td>
	<td class="cWindowFormVal">
		<input type="text" id="videoTitle" name="title" class="inputbox required" size="35" />
	</td>
</tr>


<!-- video description -->
<tr>
	<td class="cWindowFormKey">
		<label for="description" class="label title">
			<?php echo JText::_('CC VIDEO DESCRIPTION'); ?>
		</label>
	</td>
	<td class="cWindowFormVal">
		<textarea id="description" name="description" class="inputbox fullwidth"></textarea>
	</td>
</tr>


<!-- video category -->
<tr>
	<td class="cWindowFormKey">
		<label for="category" class="label title">
			<?php echo JText::_('CC VIDEO CATEGORY'); ?>
		</label>
	</td>
	<td class="cWindowFormVal">
		<?php echo $list['category']; ?>
	</td>
</tr>


<?php if ($creatorType != VIDEO_GROUP_TYPE) { ?>
<!-- video privacy -->
<tr>
	<td class="cWindowFormKey">
		<label for="category" class="label title">
			<?php echo JText::_('CC VIDEO WHO CAN SEE'); ?>
		</label>
	</td>
	<td class="cWindowFormVal">
		<div>
			<input id="privacy-public" type="radio" name="privacy" value="0" checked="checked" />
			<label for="privacy-public" class="lblradio"><?php echo JText::_('CC PRIVACY PUBLIC');?></label>
		</div>
		
		<div>
	    	<input id="privacy-members" type="radio" name="privacy" value="20" />
			<label for="privacy-members" class="lblradio"><?php echo JText::_('CC PRIVACY SITE MEMBERS');?></label>
		</div>
		
		<div>
	        <input id="privacy-friends" type="radio" name="privacy" value="30" />
			<label for="privacy-friends" class="lblradio"><?php echo JText::_('CC PRIVACY FRIENDS');?></label>
		</div>
		
		<div>
	        <input id="privacy-me" type="radio" name="privacy" value="40"/>
			<label for="privacy-me" class="lblradio"><?php echo JText::_('CC PRIVACY ME');?></label>
		</div>
	</td>
</tr>
<?php } ?>

<tr>
	<td class="key"></td>
	<td class="value"><span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span></td>
</tr>
<tr>
	<td class="key"></td>
	<td class="value"><div class="hints"><?php if($videoUploadLimit > 0) { echo JText::sprintf('CC VIDEOS UPLOAD LIMIT STATUS', $videoUploaded, $videoUploadLimit );} ?></div></td>
</tr>
</table>

<input type="hidden" name="creatortype" value="<?php echo $creatorType; ?>" />
<input type="hidden" name="groupid" value="<?php echo $groupid; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>

</form>