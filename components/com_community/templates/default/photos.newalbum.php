<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<div style="width: 80%; margin: 0 auto;">
	<form name="jsform-photos-newalbum" id="newalbum" method="post" action="<?php echo CRoute::getURI(); ?>" class="community-form-validate">
	
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
				*<?php echo JText::_('CC ALBUM NAME');?>
			</label>
		</td>
		<td class="value">
			<input type="text" name="name" class="inputbox fullwidth required" size="35" />
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
			<textarea name="description" id="description" class="inputbox fullwidth"></textarea>
		</td>
	</tr>
	<tr>
		<td class="key"></td>
		<td class="value"><span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span></td>
	</tr>
	<tr>
		<td colspan="2">
			<?php echo $afterFormDisplay;?>
		</td>
	</tr>
	<!-- button -->
	<tr class="noLabel">
		<td class="key"></td>
		<td class="value">
			<input type="hidden" name="type" value="<?php echo $type;?>" />
			<input type="submit" value="<?php echo JText::_('CC CREATE ALBUM BUTTON');?>" class="button validateSubmit" />	
		</td>
	</tr>
	</table>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
<script type="text/javascript">
	cvalidate.init();
	cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
</script>