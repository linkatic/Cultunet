<?php
$show	= '';
if($albumid != '')
	$show	= '&albumid=' . $albumid;
?>
<!-- 
<div>
	<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=singleupload' . $show ); ?>">
		<?php echo JText::_('CC GO TO SINGLE UPLOAD');?>
	</a>
</div>
-->
<div>
	<?php echo JText::_('CC MULTIPLE UPLOAD DESCRIPTION');?>
</div>
<script type="text/javascript">
function cAlbumChangeLink(id)
{
	if(id != -1)
	{
		window.location.href	= 'index.php?option=com_community&view=photos&task=multiupload&albumid=' + id;
	}
}
</script>
<?php
if($albums)
{
?>
<div>
	<select name="albumid" onchange="cAlbumChangeLink(this.value);" class="inputbox">
	<?php
	$selected	= ( !empty( $albumid ) ) ? 'selected="selected"' : '';
	?>
		<option value="-1"<?php echo $selected;?>><?php echo JText::_('CC SELECT ALBUM');?></option>
	<?php
	foreach($albums as $album)
	{
		if($albumid != '' && ($album->id == $albumid))
		{
	?>
		<option value="<?php echo $album->id;?>" selected="selected"><?php echo $this->escape($album->name); ?></option>
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
</div>
	<?php
	// This section only proceeds when user selects an album
	if( !empty( $albumid ) )
	{
	?>
		<!-- File Upload Form -->
		<form name="jsform-photos-uploader-multi" action="<?php echo JURI::base();?>index.php?option=com_community&amp;view=photos&amp;task=upload&amp;albumid=<?php echo $albumid;?>&amp;tmpl=component&amp;<?php echo $session->getName().'='.$session->getId(); ?>" id="uploadForm" method="post" enctype="multipart/form-data">
			<input type="file" id="image-upload" class="button" name="Filedata" />
			<input type="submit" class="button" id="file-upload-submit" value="<?php echo JText::_('CC BUTTON START UPLOAD'); ?>"/>
			<ul class="upload-queue" id="upload-queue">
				<li style="display: none" />
			</ul>
			<span id="upload-clear"></span>
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	<?php
	}
}
else
{
?>
	<div>
		<span><?php echo JText::_('CC NO ALBUM'); ?></span>
		<span>
			<a href="<?php echo $createAlbum;?>">
			<?php echo JText::_('CC CREATE ALBUM NOW');?>
			</a>
		</span>
	</div>
<?php
}
?>
