<?php
/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>
<div id="profile-edit">
<form name="frmSaveProfile" id="frmSaveProfile" action="<?php echo CRoute::getURI(); ?>" method="POST" class="community-form-validate">
<?php echo $beforeFormDisplay;?>
<?php

foreach ( $fields as $name => $fieldGroup )
{
		if ($name != 'ungrouped')
		{
?>
		<div class="ctitle">
			<h2><?php echo JText::_( $name );?></h2>
		</div>
 		
<?php
		}
?>
		<table class="formtable" cellspacing="1" cellpadding="0" style="width: 98%;">
		<tbody>
			<?php
				foreach ( $fieldGroup as $f )
				{
					$f = JArrayHelper::toObject ( $f );
					$f->value	= $this->escape( $f->value );
			?>
					<tr>
	 					<td class="key"><label id="lblfield<?php echo $f->id;?>" for="field<?php echo $f->id;?>" class="label"><?php if($f->required == 1) echo '*'; ?><?php echo JText::_( $f->name );?></label></td>	 					
	 					<td class="value"><?php echo CProfileLibrary::getFieldHTML( $f , '' ); ?></td>
	 				</tr>
	 		<?php
				}
			?>
		</tbody>
		</table>
<?php
}
?>
		<table class="formtable" cellspacing="1" cellpadding="0">
			<tr>
				<td class="key"></td>
				<td class="value"><span class="hints"><?php echo JText::_( 'CC_REG_REQUIRED_FILEDS' ); ?></span></td>
			</tr>
		</table>
<?php echo $afterFormDisplay;?>
		<table class="formtable" cellspacing="1" cellpadding="0" style="width: 98%;">
		<tbody>
			<tr>
			    <td class="key"></td>
			    <td class="value">
					<input type="hidden" name="action" value="save" />
                    <input class="validateSubmit button" type="submit" value="<?php echo JText::_('CC BUTTON SAVE'); ?>" />
			    </td>
			</tr>
		</tbody>
		</table>
</form>
	<script type="text/javascript">
	    cvalidate.init();
	    cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("CC REQUIRED ENTRY MISSING")); ?>');
	</script>
</div>