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
<table class="configtable cfgdesc" border="0" cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<th colspan="2"><?php echo JText::_('BACKEND SETTINGS');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('INCOMING LINKS');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[incomingLinks]', '', $this->settings->get('incomingLinks', 0), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('CHECK FOR UPDATE');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[checkUpdates]', '', $this->settings->get('checkUpdates', 0), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('GENERAL SETTINGS');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('DISPLAY POWEREDBY');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[powerby]', '', $this->settings->get('powerby', 1), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('Date Format');?></td>
		<td><input name="settings[dateFormat]" value="<?php echo $this->settings->get('dateFormat', '%B %d, %Y');?>" class="text_area" type="text"></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('PROFILE');?></th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('USE AVATAR');?></td>
		<td><?php echo $this->lists['avatarsys']; ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('AVATAR WIDTH');?></td>
		<td><input name="settings[maxAvatarWidth]" value="<?php echo $this->settings->get('maxAvatarWidth', '80');?>" class="text_area" type="text"> (px)</td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('AVATAR HEIGHT');?></td>
		<td><input name="settings[maxAvatarHeight]" value="<?php echo $this->settings->get('maxAvatarHeight', '80');?>" class="text_area" type="text"> (px)</td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
 </table>