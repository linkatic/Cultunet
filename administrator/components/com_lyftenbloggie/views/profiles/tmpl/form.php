<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	submitform( pressbutton );
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="configtable cfgdesc" border="0" cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<th colspan="2"><?php echo JText::_('ABOUTBLOCK DATA');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('ABOUT BLOCK');?></td>
		<td><textarea name="about" cols="50" rows="9" class="text_area" id="about"><?php echo $this->row->about;?></textarea></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('FACEBOOK URL');?></td>
		<td><input name="params[facebookURL]" id="paramsfacebookURL" value="<?php echo $this->params->get('facebookURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row1">
		<td class="title"><?php echo JText::_('DIGG URL');?></td>
		<td><input name="params[diggURL]" id="paramsdiggURL" value="<?php echo $this->params->get('diggURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row0">
		<td class="title"><?php echo JText::_('DELICIOUS URL');?></td>
		<td><input name="params[deliciousURL]" id="paramsdeliciousURL" value="<?php echo $this->params->get('deliciousURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row1">
		<td class="title"><?php echo JText::_('TECHNORATI URL');?></td>
		<td><input name="params[technoratiURL]" id="paramstechnoratiURL" value="<?php echo $this->params->get('technoratiURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row0">
		<td class="title"><?php echo JText::_('TWITTER URL');?></td>
		<td><input name="params[twitterURL]" id="paramstwitterURL" value="<?php echo $this->params->get('twitterURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row1">
		<td class="title"><?php echo JText::_('FLICKR URL');?></td>
		<td><input name="params[flickrURL]" id="paramsflickrURL" value="<?php echo $this->params->get('flickrURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row0">
		<td class="title"><?php echo JText::_('MYBLOGLOG URL');?></td>
		<td><input name="params[mybloglogURL]" id="paramsmybloglogURL" value="<?php echo $this->params->get('mybloglogURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row1">
		<td class="title"><?php echo JText::_('FRIENDFEED URL');?></td>
		<td><input name="params[ffindURL]" id="paramsffindURL" value="<?php echo $this->params->get('ffindURL'); ?>" class="text_area" type="text" style="width:80%;" /></td>
	</tr>	
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="avatar" value="<?php echo $this->row->avatar; ?>" />
<input type="hidden" name="user_id" value="<?php echo $this->row->user_id; ?>" />
<input type="hidden" name="controller" value="profiles" />
<input type="hidden" name="view" value="profile" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>