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

<table class="video-addTypes" cellpadding="5">
<tr>
    <td class="video-addType link">
        <h2 class="video-addType-name"><?php echo JText::_('CC LINK VIDEO'); ?></h2>
        <p class="video-addType-description"><?php echo JText::_('CC VIDEO LINK ADDTYPE DESC'); ?></p>
        
        <ul class="video-providers">          
            <li class="video-provider">YouTube</li>
            <li class="video-provider">Yahoo Video</li>
            <li class="video-provider">MySpace Video</li>
            <li class="video-provider">Flickr</li>
            <li class="video-provider">Vimeo</li>
            <li class="video-provider">Metacafe</li>
            <li class="video-provider">Blip.tv</li>
            <li class="video-provider">Dailymotion</li>
            <li class="video-provider">Break</li>
            <li class="video-provider">Live Leak</li>
            <li class="video-provider">Viddler</li> 
        </ul>
    </td>
<?php
if( $enableVideoUpload )
{
?>
	<td class="video-addType upload">
        <div class="upload-video-field">
            <h2 class="video-addType-name"><?php echo JText::_('CC UPLOAD VIDEO'); ?></h2>
            <p class="video-addType-description"><?php echo JText::_('CC VIDEO FILE ADDTYPE DESC'); ?></p>
            <ul class="video-uploadRules">
                <li class="video-uploadRule"><?php echo JText::sprintf('CC VIDEO UPLOAD RULE SIZE', $uploadLimit); ?></li>
                <li class="video-uploadRule"><?php echo JText::_('CC VIDEO UPLOAD RULE LENGTH'); ?></li>
                <li class="video-uploadRule"><?php echo JText::_('CC VIDEO UPLOAD RULE FORMAT'); ?></li>
            </ul> 
            
        </div>
	</td>
<?php
}
?>
</tr>
<tr>
	<td style="text-align: center;">
		<button class="video-action button" onclick="joms.videos.linkVideo('<?php echo $creatorType; ?>', '<?php echo $groupid; ?>');"/><?php echo JText::_('CC NEXT'); ?></button>
	</td>
<?php
if( $enableVideoUpload )
{
?>
	<td style="text-align: center;">
		<button class="video-action button" onclick="joms.videos.uploadVideo('<?php echo $creatorType; ?>', '<?php echo $groupid; ?>');"/><?php echo JText::_('CC NEXT'); ?></button>
	</td>
<?php
}
?>
</tr>
