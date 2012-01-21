<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	album	An object of CTableAlbum
 */
defined('_JEXEC') or die();
?>
<form name="newalbum" id="newalbum" method="post" action="<?php echo CRoute::getURI(); ?>" class="community-form-validate">

<table class="formtable" cellspacing="1" cellpadding="0">
<!-- name -->
<tr>
	<td class="key">
		<label for="name" class="label title">
			*<?php echo JText::_('CC ALBUM NAME');?>
		</label>
	</td>
	<td class="value">
		<input type="text" id="name" name="name" class="required" size="35" value="<?php echo $this->escape($album->name); ?>" />
	</td>
</tr>

<!-- description -->
<tr>
	<td class="key">
		<label for="description" class="label title">
			<?php echo JText::_('CC ALBUM DESCRIPTION');?>
		</label>
	</td>
	<td class="value">
		<textarea name="description" id="description" class="description"><?php echo $this->escape($album->description); ?></textarea>
	</td>
</tr>

<tr>
	<td class="key"></td>
	<td class="value"><span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span></td>
</tr>

<!-- button -->
<tr>
	<td class="key"></td>
	<td class="value">
		<input type="hidden" name="albumid" value="<?php echo $album->id; ?>" />
		<input type="hidden" name="type" value="<?php echo $type;?>" />	
		<input type="submit" class="button validateSubmit" value="<?php echo JText::_('CC SAVE ALBUM BUTTON');?>" />
		<?php echo JHTML::_( 'form.token' ); ?>	
	</td>
</tr>
</table>
</form>
<script type="text/javascript">
	cvalidate.init();
	cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
</script>