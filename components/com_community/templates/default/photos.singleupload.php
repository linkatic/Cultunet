<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();


$show	= '';
if($albumid != '')
	$show	= '&albumid=' . $albumid;
?>
<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=multiupload' . $show ); ?>">
	<?php echo JText::_('CC GO TO MULTIPLE UPLOADS');?>
</a>
<form name="newalbum" method="post" action="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=upload'); ?>" enctype="multipart/form-data" class="community-form-validate">
<div>
	<span style="float: left; width: 30%;"><?php echo JText::_('CC SELECT ALBUM');?></span>
	<span>
		<select name="albumid" style="width: 30%;">
<?php
			foreach($albums as $album)
			{
				$selected	= '';
				if($albumid != '' && ($albumid == $album->id))
				{
					$selected	.= ' selected="selected"';
				}
?>
					<option value="<?php echo $album->id; ?>"<?php echo $selected;?>><?php echo $this->escape($album->name); ?></option>
<?php
			}
?>
		</select>
	</span>
</div>
<div>
	<span style="float: left; width: 30%;"><?php echo JText::_('CC SELECT FILE');?></span>
	<span><input type="file" name="Filedata" class="required" /></span>
</div>
<div>
	<span style="float: left; width: 30%;"><?php echo JText::_('CC SET AS ALBUM COVER'); ?></span>
	<span><input type="checkbox" name="default" value="1" /></span>
</div>
<div>
	<input type="submit" class="button validateSubmit" value="<?php echo JText::_('CC BUTTON UPLOAD NOW');?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</div>
</form>
<script type="text/javascript">
	cvalidate.init();
	cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
</script>