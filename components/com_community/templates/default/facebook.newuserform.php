<?php
defined('_JEXEC') or die();
?>
<h2 style="text-decoration: underline;margin-bottom: 10px;"><?php echo JText::_('CC NEW MEMBER');?></h2>
<div style="margin-bottom: 5px;"><?php echo JText::_('CC NEW MEMBER DESCRIPTION');?></div>
<table width="100%">
	<tr>
	    <td width="30%" valign="top"><label for="newname"><?php echo JText::_('CC NAME');?></label></td>
	    <td><input type="text" id="newname" class="inputbox" size="30" value="<?php echo $userInfo['name'];?>" onblur="joms.connect.checkRealname(this.value);"/><div id="error-newname" class="small" style="display: none;color: red;"></div></td>
	</tr>
	<tr>
		<td valign="top"><label for="newusername"><?php echo JText::_('CC USERNAME');?></label></td>
		<td><input type="text" id="newusername" class="inputbox required" size="30" onblur="joms.connect.checkUsername(this.value)"/><div id="error-newusername" class="small" style="display: none;color: red;"></div></td>
	</tr>
	<tr>
		<td valign="top"><label for="newemail"><?php echo JText::_('CC EMAIL');?></label></td>
		<td><input type="text" id="newemail" value="<?php echo $userInfo['email'];?>" class="inputbox required" size="30" onblur="joms.connect.checkEmail(this.value);" /><div id="error-newemail" class="small" style="display: none;color: red;"></div></td>
	</tr>
</table>