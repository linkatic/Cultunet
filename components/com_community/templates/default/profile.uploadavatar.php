<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	my	Current browser's CUser object.
 **/
defined('_JEXEC') or die();
?>

<?php if ($firstLogin) { ?>
<div class="skipLink">
	<a href="<?php echo $skipLink; ?>"class="saveButton"><span><?php echo JText::_('CC SKIP UPLOAD AVATAR'); ?></span></a>
</div>
<?php } ?>

<div class="cModule">

	<p class="info"><?php echo JText::_('CC UPLOAD NEW PICTURE DESCRIPTION'); ?></p>

	<form name="jsform-profile-uploadavatar" action="<?php echo CRoute::getURI(); ?>" id="uploadForm" method="post" enctype="multipart/form-data">
    	<input class="inputbox button" type="file" id="file-upload" name="Filedata" />
		<input class="button" size="30" type="submit" id="file-upload-submit" value="<?php echo JText::_('CC BUTTON UPLOAD PICTURE'); ?>">
	    <input type="hidden" name="action" value="doUpload" />
	</form>
	
	<p class="info"><?php echo JText::sprintf('CC MAX FILE SIZE FOR UPLOAD' , $uploadLimit ); ?></p>

</div>


<div class="cModule avatarPreview leftside">	
	<h3><?php echo JText::_('CC PICTURE LARGE HEADING');?></h3>

	<p><?php echo JText::_('CC LARGE PICTURE DESCRIPTION'); ?></p>
	
	<div class="imagePreview">
		<img src="<?php echo $my->getAvatar();?>" alt="<?php echo JText::_('CC LARGE PICTURE DESCRIPTION'); ?>" title="<?php echo JText::_('CC LARGE PICTURE DESCRIPTION'); ?>" />
	</div>
	
	
</div>

<div class="cModule avatarPreview rightside">		
	<h3><?php echo JText::_('CC PICTURE THUMB HEADING');?></h3>
	
	<p><?php echo JText::_('CC SMALL PICTURE DESCRIPTION'); ?></p>
	
	<div class="imagePreview">		
		<img src="<?php echo $my->getThumbAvatar();?>" alt="<?php echo JText::_('CC SMALL PICTURE DESCRIPTION'); ?>" title="<?php echo JText::_('CC SMALL PICTURE DESCRIPTION'); ?>" />
	</div>
	
   	
</div>