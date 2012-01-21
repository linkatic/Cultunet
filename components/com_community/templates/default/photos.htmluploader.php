<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<?php
if( $albums )
{
?>
	<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_community/assets/ajaxfileupload.pack.js"></script>
	<script type="text/javascript" language="javascript">
	function submitForm()
	{
		joms.jQuery('#changeAlbum').submit();
	}
	</script>
	<form name="changeAlbum" id="changeAlbum" action="<?php echo CRoute::getURI();?>" method="POST">
	<div>
		<div><strong><?php echo JText::_( 'CC SELECT PHOTO ALBUM' ); ?></strong></div>
		<select name="albumid" onchange="submitForm();" class="inputbox">
		<?php
		$selected	= ( $albumId == -1 ) ? 'selected="selected"' : '';
		?>
			<option value="-1"<?php echo $selected;?>><?php echo JText::_('CC SELECT ALBUM');?></option>
		<?php
		foreach($albums as $album)
		{
			if($albumId != '' && ($album->id == $albumId))
			{
		?>
			<option value="<?php echo $album->id;?>" selected="selected"><?php echo $this->escape($album->name) ;?></option>
		<?php
			}
			else
			{
		?>
			<option value="<?php echo $album->id;?>"><?php echo $this->escape($album->name); ?></option>
		<?php
			}
		}
		?>
		</select>
		<?php
		if(!empty($albumId) && $albumId != -1 )
		{
		?>
		<span><a class="icon-photos" href="<?php echo $viewAlbumLink;?>" target="_blank"><?php echo JText::_('CC UPLOAD VIEW ALBUM');?></a></span>
		<?php
		}
		?>
	</div>
	<div></div>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>

	<div id="community-photo-items" class="photo-list-item" style="display:none">
		<div class="container"></div>
	</div>
	<br/>
	<div class="hints">
		<?php 
			if($photoUploadLimit > 0)
				echo JText::sprintf('CC UPLOAD LIMIT STATUS', $photoUploaded, $photoUploadLimit );
		?>
	</div>
	<?php
	// This section only proceeds when user selects an album
	if( !empty( $albumId ) && $albumId != -1 )
	{
	?>
	<script type="text/javascript" language="javascript">
	joms.uploader.postUrl 		= '<?php echo CRoute::_('index.php?option=com_community&view=photos&task=jsonupload&no_html=1&tmpl=component&defaultphoto=DEFAULT_PHOTOS&nextupload=NXUP&albumid=' . $albumId , false );?>';
	joms.uploader.uploadText	= '<?php echo JText::_('CC PHOTO UPLOADING');?>';
	joms.uploader.originalPostUrl = joms.uploader.postUrl;
	joms.jQuery(document).ready( function() {
		joms.uploader.addNewUpload();
		
	});
	</script>
	
	<div class="clr"></div>
	<div id="photoupload-container">
		<div id="photoupload" class="upload-form">
			<a class="remove" href="javascript:void(0);"></a>
			<input class="text input" type="file" onchange="joms.uploader.addNewUpload();" size="35" name="Filedata" id="Filedata" />
			<span>
				<input type="checkbox" name="default" value="1" /><?php echo JText::_('CC SET AS ALBUM COVER'); ?>
			</span>
			<input type="hidden" name="elementIndex" class="elementIndex" />
		</div>
	</div>

	<div>
		<button class="button button-upload" onclick="joms.uploader.addNewUpload();" id="new-upload-button"><?php echo JText::_('CC UPLOAD ANOTHER PHOTO');?></button>
		<button class="button button-upload" onclick="joms.uploader.startUpload();" id="upload-photos-button"><?php echo JText::_('CC BUTTON START UPLOAD');?></button>
	</div>
	<div><?php echo JText::sprintf('CC MAXIMUM UPLOAD LIMIT' , $uploadLimit ); ?></div>
	<?php
	}
	?>
<?php
}
else
{
?>
	<div>
		<span><?php echo JText::_('CC NO ALBUM'); ?></span>
		<span>
			<a href="<?php echo $createAlbumLink;?>">
			<?php echo JText::_('CC CREATE ALBUM NOW');?>
			</a>
		</span>
	</div>
<?php
}
?>