<?php
defined('_JEXEC') or die();
?>
<h2 style="text-decoration: underline;margin-bottom: 10px;"><?php echo JText::_('CC EXISTING SITE MEMBER');?></h2>
<div style="margin-bottom: 5px;"><?php echo JText::_('CC EXISTING SITE MEMBER DESCRIPTION');?></div>
<table width="100%">
	<tr>
	    <td width="30%" valign="top"><label for="existingusername"><?php echo JText::_('CC USERNAME');?></label></td>
	    <td><input type="text" id="existingusername" class="inputbox" size="30" /></td>
	</tr>
	<tr>
		<td valign="top"><label for="existingpassword"><?php echo JText::_('CC PASSWORD');?></label></td>
		<td><input type="password" id="existingpassword" class="inputbox" size="30" /></td>
	</tr>
</table>
<div style="color: red;margin-top:20px;"><?php echo JText::_('CC LINKING NOTICE');?></div>